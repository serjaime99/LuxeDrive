let searchInput = document.getElementById("searchByName");
let container = document.getElementById('container');
let userId = document.body.getAttribute('data-user-id');

function createVehicleCard(result) {
    let carImg = "https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80";
    const marque = (result.marque || "").toLowerCase();
    
    if (marque.includes('ferrari')) carImg = "https://images.unsplash.com/photo-1592198084033-aade902d1aae?auto=format&fit=crop&w=800&q=80";
    else if (marque.includes('porsche')) carImg = "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80";
    else if (marque.includes('audi')) carImg = "https://images.unsplash.com/photo-1542281286-9e0a16bb7366?auto=format&fit=crop&w=800&q=80";

    const buttonLink = (userId === 'guest') 
        ? `../authentification/login.php`
        : `reservation.php?vehiculeId=${result.vehicule_id}&clientId=${userId}`;
    
    const buttonText = (userId === 'guest') ? 'Sign In' : 'Book Now';

    return `
        <div class="bg-white rounded-lg shadow-soft overflow-hidden transition-all hover:shadow-lg">
            <img src="${carImg}" alt="Car" class="w-full h-48 object-cover">
            <div class="p-4">
                <span class="text-sm text-blue-600 font-medium">${result.nom || 'Premium'}</span>
                <h3 class="text-xl font-bold text-gray-900 mt-1">${result.marque} ${result.modele}</h3>
                <p class="text-gray-600">Performance & Luxury</p>
                <div class="mt-4 flex justify-between items-center border-t pt-4">
                    <span class="text-2xl font-bold text-gray-900">${result.prix} $<span class="text-sm text-gray-600">/day</span></span>
                    <a href="${buttonLink}" class="bg-gradient-primary text-white px-4 py-2 rounded hover:bg-gradient-primary transition-all">${buttonText}</a>
                </div>
            </div>
        </div>`;
}

if (searchInput) {
    searchInput.addEventListener('input', _ => {
        let searchValue = searchInput.value;
        let conn = new XMLHttpRequest();
        conn.open("GET", `../classes/searchByName.php?letHimCoock=${searchValue}`);
        conn.send();
        conn.onload = _ => {
            if (conn.status === 200) {
                try {
                    let search = JSON.parse(conn.responseText);
                    container.innerHTML = "";
                    search.forEach(result => {
                        container.innerHTML += createVehicleCard(result);
                    });
                } catch(e) { console.error(e); }
            }
        }
    });
}

let categoryFilter = document.getElementById('categoryFilter');
if (categoryFilter) {
    categoryFilter.addEventListener('change', () => {
        let categoryId = categoryFilter.value;
        let conn = new XMLHttpRequest();
        conn.open('GET', `../classes/selectByCat.php?category_id=${categoryId}`);
        conn.send();
        conn.onload = function () {
            if (conn.status === 200) {
                try {
                    let vehicles = JSON.parse(conn.responseText);
                    container.innerHTML = '';
                    vehicles.forEach(vehicle => {
                        container.innerHTML += createVehicleCard(vehicle);
                    });
                } catch(e) { console.error(e); }
            }
        };
    });
}
