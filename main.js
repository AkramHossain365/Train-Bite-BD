// ================= API CONFIGURATION =================
// সব API কল এই base URL দিয়ে হবে। আপনার সার্ভার অনুযায়ী বদলান।
const API_BASE = 'https://train-bite-bd.site.je/api';

// ================= GLOBAL STATE =================
let cart = [];
let currentUser = null; // null = Guest, Object = Logged In User
let selectedDeliveryStation = "";
let userOrderHistory = [];

// ================= API HELPERS =================

async function apiPost(endpoint, payload) {
    const url = `${API_BASE}/${endpoint}`;

    console.log('API Request:', url);
    console.log('API Payload:', payload);

    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    console.log('API Status:', res.status);
    console.log('API Content-Type:', res.headers.get('content-type'));

    const text = await res.text();

    console.log('API Raw Response:', text);

    if (!res.ok) {
        throw new Error(`HTTP ${res.status}: ${text}`);
    }

    try {
        return JSON.parse(text);
    } catch (error) {
        throw new Error(`Invalid JSON response: ${text}`);
    }
}


async function apiGet(endpoint, params = {}) {
    const query = new URLSearchParams(params).toString();

    const url =
        `${API_BASE}/${endpoint}${query ? '?' + query : ''}`;

    console.log('GET API Request:', url);

    const res = await fetch(url);

    console.log('GET API Status:', res.status);
    console.log('GET API Content-Type:', res.headers.get('content-type'));

    const text = await res.text();

    console.log('GET API Raw Response:', text);

    if (!res.ok) {
        throw new Error(`HTTP ${res.status}: ${text}`);
    }

    try {
        return JSON.parse(text);
    } catch (error) {
        throw new Error(`Invalid JSON response: ${text}`);
    }
}


// ================= DOM ELEMENTS =================

const navbar = document.getElementById('navbar');
const hamburgerBtn = document.getElementById('hamburger-btn');
const hamburgerIcon = document.getElementById('hamburger-icon');

const authModal = document.getElementById('auth-modal');
const openAuthBtn = document.getElementById('open-auth-btn');
const closeAuthModal = document.getElementById('close-auth-modal');

const tabLoginBtn = document.getElementById('tab-login-btn');
const tabRegisterBtn = document.getElementById('tab-register-btn');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

const profileModal = document.getElementById('profile-modal');
const closeProfileModal = document.getElementById('close-profile-modal');
const logoutBtn = document.getElementById('logout-btn');

const cartDrawer = document.getElementById('cart-drawer');
const openCartBtn = document.getElementById('open-cart-btn');
const closeCartBtn = document.getElementById('close-cart-btn');
const cartCountBadge = document.getElementById('cart-count');
const cartItemsContainer = document.getElementById('cart-items-container');
const cartTotalPrice = document.getElementById('cart-total-price');

const checkoutModal = document.getElementById('checkout-modal');
const checkoutBtn = document.getElementById('checkout-btn');
const closeCheckoutModal = document.getElementById('close-checkout-modal');
const checkoutForm = document.getElementById('checkout-form');


// ================= 1. MOBILE NAVBAR TOGGLE =================

hamburgerBtn.addEventListener('click', () => {
    navbar.classList.toggle('active');
    hamburgerIcon.classList.toggle('fa-bars');
    hamburgerIcon.classList.toggle('fa-xmark');
});


// ================= 2. AUTHENTICATION & PASSWORD VALIDATION =================

openAuthBtn.addEventListener('click', () => {
    authModal.classList.add('active');
});

closeAuthModal.addEventListener('click', () => {
    authModal.classList.remove('active');
});


tabLoginBtn.addEventListener('click', () => {
    tabLoginBtn.classList.add('active');
    tabRegisterBtn.classList.remove('active');

    loginForm.classList.add('active');
    registerForm.classList.remove('active');
});


tabRegisterBtn.addEventListener('click', () => {
    tabRegisterBtn.classList.add('active');
    tabLoginBtn.classList.remove('active');

    registerForm.classList.add('active');
    loginForm.classList.remove('active');
});


// ================= REGISTRATION =================

registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const payload = {
        name: document.getElementById('reg-name').value.trim(),
        phone: document.getElementById('reg-phone').value.trim(),
        email: document.getElementById('reg-email').value.trim(),
        gender: document.getElementById('reg-gender').value,
        age: document.getElementById('reg-age').value.trim(),
        nid: document.getElementById('reg-nid').value.trim(),
        address: document.getElementById('reg-address').value.trim(),
        default_station: document.getElementById('reg-station').value,
        password: document.getElementById('reg-pass').value.trim()
    };

    console.log('Registration Payload:', payload);

    try {
        const result = await apiPost('register.php', payload);

        if (!result.success) {
            alert("⚠️ " + result.message);
            return;
        }

        alert("🎉 " + result.message);

        registerForm.reset();

        tabLoginBtn.click();

    } catch (err) {
        alert("❌ সার্ভারের সাথে সংযোগ করা যায়নি। আবার চেষ্টা করুন।");
        console.error('Registration Error:', err);
    }
});


// ================= LOGIN =================

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const phoneInput =
        document.getElementById('login-phone').value.trim();

    const passInput =
        document.getElementById('login-pass').value.trim();

    try {
        const result = await apiPost(
            'login.php',
            {
                phone: phoneInput,
                password: passInput
            }
        );

        if (!result.success) {
            alert("⚠️ " + result.message);
            return;
        }

        currentUser = result.user;

        authModal.classList.remove('active');

        loginForm.reset();

        updateUserUI();

        alert(
            `🎉 স্বাগতম ${result.user.name}! আপনার লগইন সফল হয়েছে।`
        );

    } catch (err) {
        alert("❌ সার্ভারের সাথে সংযোগ করা যায়নি। আবার চেষ্টা করুন।");
        console.error('Login Error:', err);
    }
});


// ================= UPDATE HEADER USER UI =================

function updateUserUI() {

    const userContainer =
        document.getElementById('user-status-container');

    if (currentUser) {

        userContainer.innerHTML = `
            <button
                class="btn btn-primary"
                id="open-profile-btn"
                style="background: #2ed573;"
            >
                <i class="fa-solid fa-user-check"></i>
                <span class="btn-text">${currentUser.name}</span>
            </button>
        `;

        document
            .getElementById('open-profile-btn')
            .addEventListener('click', async () => {

                document.getElementById('profile-name').textContent =
                    currentUser.name;

                document.getElementById('profile-phone').textContent =
                    currentUser.phone;

                renderProfileDetails();

                await loadOrderHistory();

                profileModal.classList.add('active');
            });

    } else {

        userContainer.innerHTML = `
            <button
                class="btn btn-primary"
                id="open-auth-btn"
            >
                <i class="fa-regular fa-user"></i>
                <span class="btn-text">Login / Register</span>
            </button>
        `;

        document
            .getElementById('open-auth-btn')
            .addEventListener('click', () => {
                authModal.classList.add('active');
            });
    }
}


// ================= RENDER PROFILE DETAILS =================

function renderProfileDetails() {

    const container =
        document.getElementById('profile-details');

    if (!container || !currentUser) return;

    const genderLabels = {
        male: 'পুরুষ',
        female: 'মহিলা',
        other: 'অন্যান্য'
    };

    const rows = [
        ['মোবাইল', currentUser.phone],
        ['ইমেইল', currentUser.email],
        [
            'লিঙ্গ',
            currentUser.gender
                ? (genderLabels[currentUser.gender] ||
                    currentUser.gender)
                : ''
        ],
        [
            'বয়স',
            currentUser.age
                ? currentUser.age + ' বছর'
                : ''
        ],
        ['NID', currentUser.nid],
        ['ঠিকানা', currentUser.address],
        [
            'নিয়মিত স্টেশন',
            currentUser.default_station
        ]
    ].filter(row => row[1]);

    if (rows.length === 0) {

        container.innerHTML =
            `<p class="empty-history-text">
                কোনো অতিরিক্ত তথ্য দেওয়া হয়নি।
            </p>`;

        return;
    }

    container.innerHTML = rows
        .map(([label, value]) => `
            <div class="profile-detail-row">
                <span class="profile-detail-label">
                    ${label}
                </span>

                <span class="profile-detail-value">
                    ${value}
                </span>
            </div>
        `)
        .join('');
}


// ================= LOAD ORDER HISTORY =================

async function loadOrderHistory() {

    userOrderHistory = [];

    if (!currentUser) return;

    try {

        const result = await apiGet(
            'get_orders.php',
            {
                user_id: currentUser.id
            }
        );

        if (
            result.success &&
            Array.isArray(result.orders)
        ) {
            userOrderHistory = result.orders;
        }

    } catch (err) {

        console.error(
            'Order history load failed:',
            err
        );
    }

    renderOrderHistory();
}


// ================= RENDER ORDER HISTORY =================

function renderOrderHistory() {

    const historyContainer =
        document.getElementById('order-history-list');

    if (userOrderHistory.length === 0) {

        historyContainer.innerHTML =
            `<p class="empty-history-text">
                এখনো কোনো খাবার অর্ডার করা হয়নি।
            </p>`;

        return;
    }

    let historyHTML = '';

    userOrderHistory.forEach(order => {

        historyHTML += `
            <div class="history-card">

                <div class="history-header">

                    <span class="history-id">
                        #${order.order_code}
                    </span>

                    <span class="history-status">
                        ${order.status}
                    </span>

                </div>

                <div class="history-details">

                    <strong>খাবার:</strong>
                    ${order.items}

                    <br>

                    <strong>মোট মূল্য:</strong>
                    ৳${order.total}

                </div>

                <div class="history-station">

                    <i class="fa-solid fa-location-dot"></i>

                    ডেলিভারি স্টেশন:
                    ${order.station}

                </div>

            </div>
        `;
    });

    historyContainer.innerHTML = historyHTML;
}


// ================= CLOSE PROFILE =================

closeProfileModal.addEventListener('click', () => {
    profileModal.classList.remove('active');
});


// ================= LOGOUT =================

logoutBtn.addEventListener('click', () => {

    currentUser = null;

    userOrderHistory = [];

    profileModal.classList.remove('active');

    updateUserUI();

    alert("আপনি অ্যাকাউন্ট থেকে লগআউট হয়েছেন!");
});


// ================= 3. TRAIN INFO SEARCH =================

const trainInput =
    document.getElementById('train-input');

const trainResultBox =
    document.getElementById('train-result-box');

const trainResultList =
    document.getElementById('train-result-list');

const trainResultCount =
    document.getElementById('train-result-count');


// ================= SEARCH TRAINS =================

async function searchTrains(query) {

    trainResultBox.style.display = 'block';

    trainResultList.innerHTML =
        `<p class="train-loading">
            <i class="fa-solid fa-spinner fa-spin"></i>
            খোঁজা হচ্ছে...
        </p>`;

    trainResultCount.textContent = '';

    try {

        const result = await apiGet(
            'trains.php',
            {
                q: query
            }
        );

        if (!result.success) {

            trainResultList.innerHTML =
                `<p class="train-empty">
                    ${result.message ||
                    'সার্চ ব্যর্থ হয়েছে।'}
                </p>`;

            return;
        }

        const trains =
            Array.isArray(result.trains)
                ? result.trains
                : [];

        trainResultCount.textContent =
            `মোট ${result.count || trains.length}টি ট্রেন পাওয়া গেছে` +
            `${query ? ` — "${query}"` : ''}`;

        renderTrainResults(trains);

    } catch (err) {

        trainResultList.innerHTML =
            `<p class="train-empty">
                ❌ সার্ভারের সাথে সংযোগ করা যায়নি।
            </p>`;

        console.error(
            'Train search error:',
            err
        );
    }
}


// ================= RENDER TRAIN RESULTS =================

function renderTrainResults(trains) {

    if (trains.length === 0) {

        trainResultList.innerHTML =
            `<p class="train-empty">
                কোনো ট্রেন পাওয়া যায়নি।
                নম্বর (যেমন: 701) বা নাম
                (যেমন: Parabat) দিয়ে চেষ্টা করুন।
            </p>`;

        return;
    }

    trainResultList.innerHTML =
        trains
            .map(train => trainCardHTML(train))
            .join('');

    trainResultList
        .querySelectorAll('.train-select-btn')
        .forEach(btn => {

            btn.addEventListener('click', () => {

                const no =
                    btn.getAttribute('data-train-no');

                const train =
                    trains.find(
                        t => String(t.no) === String(no)
                    );

                if (train) {
                    selectTrainForOrdering(train);
                }
            });
        });
}


// ================= TRAIN CARD =================

function trainCardHTML(train) {

    const dash = v =>
        (v && v !== '' ? v : '—');

    const displayName =
        train.name && train.name !== ''
            ? train.name
            : 'নাম পাওয়া যায়নি';

    const route =
        Array.isArray(train.route)
            ? train.route
            : [];

    const stopsWithEta =
        routeWithEtas(train);

    const stoppages =
        stopsWithEta.length
            ? stopsWithEta.map(stop => {

                const roleClass =
                    stop.isOrigin
                        ? 'stop-origin'
                        : (
                            stop.isDestination
                                ? 'stop-dest'
                                : ''
                        );

                const tag =
                    stop.isOrigin
                        ? `<span class="stop-tag">ছাড়া</span>`
                        : (
                            stop.isDestination
                                ? `<span class="stop-tag">গন্তব্য</span>`
                                : ''
                        );

                const eta =
                    stop.eta
                        ? `
                            <span class="stop-eta">
                                <i class="fa-regular fa-clock"></i>
                                ${stop.eta}
                            </span>
                        `
                        : `
                            <span class="stop-eta muted">
                                —
                            </span>
                        `;

                return `
                    <div class="stop-item ${roleClass}">

                        <span class="stop-dot"></span>

                        <span class="stop-name">
                            ${stop.name}
                            ${tag}
                        </span>

                        ${eta}

                    </div>
                `;

            }).join('')
            : `
                <p class="train-empty">
                    এই ট্রেনের রুট/স্টপেজ তথ্য পাওয়া যায়নি।
                </p>
            `;

    const offDay =
        train.off_day && train.off_day !== ''
            ? train.off_day
            : 'নেই (প্রতিদিন চলে)';

    const offClass =
        (
            train.off_day &&
            train.off_day !== 'None' &&
            train.off_day !== ''
        )
            ? 'off-active'
            : 'off-none';

    return `
        <div class="train-card">

            <div class="train-card-head">

                <div>

                    <span class="train-no">
                        #${train.no}
                    </span>

                    <span class="train-name">
                        ${displayName}
                    </span>

                </div>

                <span class="train-offday ${offClass}">

                    <i class="fa-regular fa-calendar-xmark"></i>

                    বন্ধ: ${offDay}

                </span>

            </div>


            <div class="train-journey">

                <div class="journey-point">

                    <span class="journey-station">
                        ${dash(train.from)}
                    </span>

                    <span class="journey-time">

                        <i class="fa-solid fa-arrow-up-from-bracket"></i>

                        ছেড়ে যায়
                        ${dash(train.departure)}

                    </span>

                </div>


                <div class="journey-arrow">

                    <i class="fa-solid fa-arrow-right-long"></i>

                    <small>
                        ${route.length
                            ? route.length + 'টি স্টেশন'
                            : ''}
                    </small>

                </div>


                <div class="journey-point">

                    <span class="journey-station">
                        ${dash(train.to)}
                    </span>

                    <span class="journey-time">

                        <i class="fa-solid fa-arrow-down-to-bracket"></i>

                        পৌঁছায়
                        ${dash(train.arrival)}

                    </span>

                </div>

            </div>


            <div class="train-route-wrap">

                <p class="train-route-title">

                    <i class="fa-solid fa-route"></i>

                    রুট ও স্টপেজ
                    (আনুমানিক সময়সহ)

                </p>

                <div class="train-route-list">

                    ${stoppages}

                </div>

            </div>


            <button
                type="button"
                class="btn btn-primary btn-full train-select-btn"
                data-train-no="${train.no}"
            >

                <i class="fa-solid fa-utensils"></i>

                এই ট্রেনে খাবার অর্ডার করুন

            </button>

        </div>
    `;
}


// ================= ESTIMATED TRAIN TIMES =================

function minutesFromTime(hhmm) {

    if (
        !hhmm ||
        !/^\d{1,2}:\d{2}$/.test(hhmm)
    ) {
        return null;
    }

    const [h, m] =
        hhmm.split(':').map(Number);

    return h * 60 + m;
}


function formatTime(totalMinutes) {

    const t =
        ((totalMinutes % 1440) + 1440) % 1440;

    const h =
        String(Math.floor(t / 60))
            .padStart(2, '0');

    const m =
        String(t % 60)
            .padStart(2, '0');

    return `${h}:${m}`;
}


function routeWithEtas(train) {

    const route =
        Array.isArray(train.route)
            ? train.route
            : [];

    if (route.length === 0) {
        return [];
    }

    const depMin =
        minutesFromTime(train.departure);

    const arrMin =
        minutesFromTime(train.arrival);

    let journey = null;

    if (
        depMin !== null &&
        arrMin !== null
    ) {

        journey = arrMin - depMin;

        if (journey <= 0) {
            journey += 1440;
        }
    }

    return route.map((stop, i) => {

        const isOrigin = i === 0;

        const isDestination =
            i === route.length - 1;

        let eta = '';

        if (
            depMin !== null &&
            journey !== null &&
            route.length > 1
        ) {

            const fraction =
                i / (route.length - 1);

            eta =
                formatTime(
                    depMin +
                    Math.round(journey * fraction)
                );

        } else if (
            isOrigin &&
            depMin !== null
        ) {

            eta = formatTime(depMin);

        } else if (
            isDestination &&
            arrMin !== null
        ) {

            eta = formatTime(arrMin);
        }

        return {
            name: stop,
            eta: eta,
            isOrigin: isOrigin,
            isDestination: isDestination,
            isOrderable: true
        };
    });
}


// ================= DELIVERY STATION PICKER =================

let selectedTrain = null;


function renderStationOptions(train) {

    if (train) {
        selectedTrain = train;
    }

    const stationContainer =
        document.getElementById('station-options');

    const heading =
        document.getElementById('station-select-heading');

    stationContainer.innerHTML = '';

    if (!selectedTrain) {

        stationContainer.innerHTML =
            `<p class="empty-history-text">
                প্রথমে একটি ট্রেন সার্চ করে সেটি সিলেক্ট করুন।
            </p>`;

        return;
    }

    const stops =
        routeWithEtas(selectedTrain);

    if (heading) {

        heading.innerHTML =
            `<i class="fa-solid fa-location-dot"></i> ` +
            `<strong>
                ${selectedTrain.name || 'ট্রেন'}
                (#${selectedTrain.no})
            </strong> — ` +
            `কোন স্টপেজে খাবার চাই?
            (ছাড়ার স্টেশন থেকে গন্তব্যের মধ্যে
            যেকোনো একটি বেছে নিন) — ` +
            `আনুমানিক সময় দেওয়া আছে`;
    }

    if (stops.length === 0) {

        stationContainer.innerHTML =
            `<p class="empty-history-text">
                এই ট্রেনের স্টপেজ তথ্য পাওয়া যায়নি।
            </p>`;

        return;
    }

    const orderableStops =
        stops.filter(s => !s.isOrigin);

    orderableStops.forEach((stop, idx) => {

        const card =
            document.createElement('div');

        card.className = 'station-card';

        if (
            stop.name === selectedDeliveryStation
        ) {
            card.classList.add('selected');
        }

        if (
            !selectedDeliveryStation &&
            idx === orderableStops.length - 1
        ) {

            card.classList.add('selected');

            selectedDeliveryStation =
                stop.name;
        }

        const roleTag =
            stop.isDestination
                ? `<span class="stop-tag">গন্তব্য</span>`
                : '';

        const etaTag =
            stop.eta
                ? `
                    <span class="station-time">
                        <i class="fa-regular fa-clock"></i>
                        পৌঁছাবে আনুমানিক ${stop.eta}
                    </span>
                `
                : `
                    <span class="station-time muted">
                        সময় পাওয়া যায়নি
                    </span>
                `;

        card.innerHTML = `
            <span class="station-name">

                <i class="fa-solid fa-train"></i>

                ${stop.name}

                ${roleTag}

            </span>

            ${etaTag}
        `;

        card.addEventListener('click', () => {

            document
                .querySelectorAll(
                    '#station-options .station-card'
                )
                .forEach(c =>
                    c.classList.remove('selected')
                );

            card.classList.add('selected');

            selectedDeliveryStation =
                stop.name;
        });

        stationContainer.appendChild(card);
    });
}


// ================= SELECT TRAIN =================

function selectTrainForOrdering(train) {

    selectedTrain = train;

    selectedDeliveryStation = '';

    renderStationOptions(train);

    document.getElementById('display-pnr').textContent =
        `${train.name || 'ট্রেন'} — #${train.no} (${train.from} → ${train.to})`;

    document.getElementById(
        'station-selector-box'
    ).style.display = 'block';

    document.getElementById(
        'station-selector-box'
    ).scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}


// ================= TRAIN SEARCH FORM =================

let trainSearchTimer = null;


document
    .getElementById('search-form')
    .addEventListener('submit', (e) => {

        e.preventDefault();

        clearTimeout(trainSearchTimer);

        searchTrains(
            trainInput.value.trim()
        );
    });


// ================= LIVE TRAIN SEARCH =================

trainInput.addEventListener('input', () => {

    clearTimeout(trainSearchTimer);

    trainSearchTimer =
        setTimeout(() => {

            searchTrains(
                trainInput.value.trim()
            );

        }, 350);
});


// ================= QUICK PICK CHIPS =================

document
    .querySelectorAll('#train-quick-chips .chip')
    .forEach(chip => {

        chip.addEventListener('click', () => {

            trainInput.value =
                chip.getAttribute(
                    'data-train-q'
                );

            searchTrains(
                trainInput.value
            );
        });
    });


// ================= CLOSE TRAIN RESULTS =================

document
    .getElementById('train-result-close')
    .addEventListener('click', () => {

        trainResultBox.style.display =
            'none';
    });


// ================= 4. FOOD CATEGORY FILTER =================

const categoryButtons =
    document.querySelectorAll('.cat-btn');

const foodCards =
    document.querySelectorAll('.food-card');


categoryButtons.forEach(button => {

    button.addEventListener('click', () => {

        categoryButtons.forEach(btn =>
            btn.classList.remove('active')
        );

        button.classList.add('active');

        const category =
            button.getAttribute(
                'data-category'
            );

        foodCards.forEach(card => {

            if (
                category === 'all' ||
                card.getAttribute(
                    'data-category'
                ) === category
            ) {

                card.style.display =
                    'block';

            } else {

                card.style.display =
                    'none';
            }
        });
    });
});


// ================= 5. SHOPPING CART SYSTEM =================

openCartBtn.addEventListener('click', () => {
    cartDrawer.classList.add('active');
});


closeCartBtn.addEventListener('click', () => {
    cartDrawer.classList.remove('active');
});


function addToCart(
    id,
    title,
    price,
    img
) {

    const existingItem =
        cart.find(item => item.id === id);

    if (existingItem) {

        existingItem.qty += 1;

    } else {

        cart.push({
            id,
            title,
            price,
            img,
            qty: 1
        });
    }

    updateCartUI();

    cartDrawer.classList.add('active');
}


function updateCartUI() {

    const totalQty =
        cart.reduce(
            (acc, item) =>
                acc + item.qty,
            0
        );

    cartCountBadge.textContent =
        totalQty;

    if (cart.length === 0) {

        cartItemsContainer.innerHTML =
            `<p class="empty-cart-text">
                আপনার কার্ট খালি।
                মেনু থেকে খাবার যোগ করুন!
            </p>`;

        cartTotalPrice.textContent =
            '৳0';

        return;
    }

    let itemsHTML = '';

    let total = 0;

    cart.forEach(item => {

        total +=
            item.price *
            item.qty;

        itemsHTML += `
            <div class="cart-item">

                <div>

                    <div class="cart-item-title">
                        ${item.title}
                    </div>

                    <div class="cart-item-price">
                        ৳${item.price} x ${item.qty}
                    </div>

                </div>

                <div class="qty-controls">

                    <button
                        class="qty-btn"
                        onclick="changeQty(${item.id}, -1)"
                    >
                        -
                    </button>

                    <span>
                        ${item.qty}
                    </span>

                    <button
                        class="qty-btn"
                        onclick="changeQty(${item.id}, 1)"
                    >
                        +
                    </button>

                </div>

            </div>
        `;
    });

    cartItemsContainer.innerHTML =
        itemsHTML;

    cartTotalPrice.textContent =
        `৳${total}`;
}


// ================= CHANGE CART QUANTITY =================

function changeQty(id, change) {

    const item =
        cart.find(i => i.id === id);

    if (item) {

        item.qty += change;

        if (item.qty <= 0) {

            cart =
                cart.filter(
                    i => i.id !== id
                );
        }
    }

    updateCartUI();
}


// ================= 6. CHECKOUT =================

checkoutBtn.addEventListener('click', () => {

    if (cart.length === 0) {

        alert(
            "আপনার কার্টে কোনো খাবার নেই!"
        );

        return;
    }


    if (!currentUser) {

        cartDrawer.classList.remove(
            'active'
        );

        authModal.classList.add(
            'active'
        );

        alert(
            "খাবার অর্ডার নিশ্চিত করতে " +
            "প্রথমে আপনার অ্যাকাউন্টে " +
            "লগইন বা রেজিস্ট্রেশন করুন!"
        );

    } else {

        cartDrawer.classList.remove(
            'active'
        );


        if (
            !selectedDeliveryStation &&
            currentUser.default_station
        ) {

            selectedDeliveryStation =
                currentUser.default_station;

            document
                .querySelectorAll(
                    '#station-options .station-card'
                )
                .forEach(c => {

                    c.classList.toggle(
                        'selected',
                        c.textContent.includes(
                            currentUser.default_station
                        )
                    );
                });
        }


        if (!selectedDeliveryStation) {

            alert(
                "⚠️ খাবার অর্ডার করতে " +
                "প্রথমে উপরের সার্চ থেকে " +
                "একটি ট্রেন ও তার স্টপেজ বেছে নিন!"
            );

            document.getElementById(
                'station-selector-box'
            ).style.display =
                'block';

            document.getElementById(
                'station-selector-box'
            ).scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            return;
        }


        document.getElementById(
            'checkout-station-display'
        ).value =
            selectedDeliveryStation;

        checkoutModal.classList.add(
            'active'
        );
    }
});


closeCheckoutModal.addEventListener(
    'click',
    () => {
        checkoutModal.classList.remove(
            'active'
        );
    }
);


// ================= CREATE ORDER =================

checkoutForm.addEventListener(
    'submit',
    async (e) => {

        e.preventDefault();


        const items =
            cart.map(i => ({
                food_id: i.id,
                title: i.title,
                price: i.price,
                qty: i.qty
            }));


        const coachInput =
            checkoutForm.querySelector(
                'input[placeholder*="ক"]'
            );

        const seatInput =
            checkoutForm.querySelector(
                'input[placeholder*="45"]'
            );

        const paymentSelect =
            checkoutForm.querySelector(
                'select'
            );


        const payload = {

            user_id:
                currentUser.id,

            items:
                items,

            station:
                selectedDeliveryStation,

            pnr:
                document
                    .getElementById(
                        'checkout-pnr'
                    )
                    .value
                    .trim(),

            coach:
                coachInput
                    ? coachInput.value.trim()
                    : '',

            seat:
                seatInput
                    ? seatInput.value.trim()
                    : '',

            payment_method:
                paymentSelect
                    ? paymentSelect.value
                    : 'cod'
        };


        console.log(
            'Create Order Payload:',
            payload
        );


        try {

            const result =
                await apiPost(
                    'create_order.php',
                    payload
                );


            if (!result.success) {

                alert(
                    "⚠️ " +
                    result.message
                );

                return;
            }


            alert(
                `🎉 আপনার অর্ডার সফলভাবে গ্রহণ করা হয়েছে!\n` +
                `অর্ডার আইডি: #${result.order_code}\n` +
                `ডেলিভারি স্টেশন: ${result.order.station}`
            );


            cart = [];

            updateCartUI();

            checkoutForm.reset();

            checkoutModal.classList.remove(
                'active'
            );


            await loadOrderHistory();

        } catch (err) {

            alert(
                "❌ অর্ডার সেভ করা যায়নি। আবার চেষ্টা করুন।"
            );

            console.error(
                'Create Order Error:',
                err
            );
        }
    }
);


// ================= 7. CONTACT FORM =================

document
    .getElementById('contact-form')
    .addEventListener(
        'submit',
        async (e) => {

            e.preventDefault();

            const form =
                e.target;

            const inputs =
                form.querySelectorAll(
                    'input, textarea'
                );


            const payload = {

                name:
                    inputs[0]
                        ? inputs[0].value.trim()
                        : '',

                contact:
                    inputs[1]
                        ? inputs[1].value.trim()
                        : '',

                subject:
                    inputs[2]
                        ? inputs[2].value.trim()
                        : '',

                message:
                    inputs[3]
                        ? inputs[3].value.trim()
                        : ''
            };


            try {

                const result =
                    await apiPost(
                        'contact.php',
                        payload
                    );


                if (!result.success) {

                    alert(
                        "⚠️ " +
                        result.message
                    );

                    return;
                }


                alert(
                    result.message
                );

                form.reset();

            } catch (err) {

                alert(
                    "❌ বার্তা পাঠানো যায়নি।"
                );

                console.error(
                    'Contact Error:',
                    err
                );
            }
        }
    );
