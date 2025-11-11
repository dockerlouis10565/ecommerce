console.log("monitor.js loaded");

function displayinfo(info, productId) {
    const displayDiv = document.getElementById('display2');
    displayDiv.innerHTML = '';

    for (const key in info) {
        if (key !== '0') {
            const itemDiv = document.createElement('div');
            itemDiv.innerHTML = `<button style="margin: 10px 2px;border-radius: 5px;" id="${key}">${key}: ${info[key]}</button>`;
            displayDiv.appendChild(itemDiv);

            const button = itemDiv.querySelector('button');
            button.addEventListener('click', () => {
                const arr = {};
                arr["id"] = button.id;
                console.log(info);
                const match = key.match(/([a-z]+)([A-Z]*)(.*)/);
                if (match) {
                    arr["gender"] = match[1];
                    arr["genre"] = match[2].toLowerCase();
                    arr["agegroup"] = match[3];
                }
                const confirmation = confirm(`Do you want to fetch for ${arr["genre"]} ${arr["gender"]} ${arr["agegroup"]} from ${productId} database?`);

                if (confirmation) {
                    const fetched=fetch(`../../public/backend/controller.php?act=fetchspecificData&gender=${arr["gender"]}&genre=${arr["genre"]}&agegroup=${arr["agegroup"]}&id=${productId}`);
                    fetched
                        .then(response => {
                            if (response.redirected) {
                                window.location.href = response.url;
                            }
                        });
                }
                console.log(arr);
            });
        }
    }
}


document.addEventListener("DOMContentLoaded", async function () {
    const stockdiv = document.getElementById('stock');
    stockdiv.addEventListener('click', async () => {
        const response = await fetch('../../public/backend/controller.php?act=getStockData');
        if (!response.ok) {
            console.error('Network response was not ok');
            return;
        }
        const data = await response.json();
        const displayDiv = document.getElementById('display1');
        displayDiv.innerHTML = '';
        const stockcontent = {};

        data.forEach(item => {
            const a = item.product;
            if (!stockcontent[a]) {
                stockcontent[a] = {};
                stockcontent[a][0] = 0;
            }
            stockcontent[a][0] += item.quantity;

            const genderKey = item.gender;
            const genreKey = item.genre.toUpperCase();
            const ageKey = item.agegroup;

            stockcontent[a][genderKey] = (stockcontent[a][genderKey] || 0) + item.quantity;
            stockcontent[a][genderKey + genreKey] = (stockcontent[a][genderKey + genreKey] || 0) + item.quantity;
            stockcontent[a][genderKey + genreKey + ageKey] = (stockcontent[a][genderKey + genreKey + ageKey] || 0) + item.quantity;
        });
        let i = 0;
        for (const product in stockcontent) {
            const itemDiv = document.createElement('div');
            itemDiv.innerHTML = `<button style="margin: 10px 5px; border-radius: 5px;" id="${product}">${product}: Total ${stockcontent[product][0]}</button>`;
            displayDiv.appendChild(itemDiv);
        }
        buttons = document.querySelectorAll('#display1 button');
        
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.id;
                console.log(productId);
                displayinfo(stockcontent[productId], productId);
            });
        });

    });
});

