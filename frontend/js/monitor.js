document.addEventListener("DOMContentLoaded", async function() {
    const response = await fetch('../../backend/controller.php?$act=getStockData',{ 
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    });
    if (!response.ok) {
        console.error('Network response was not ok');
        return;
    }
    const data = await response.json();
    const stockdiv = document.getElementById('stock');
    stockdiv.addEventListener('click', () => {
        
    });
    /**data.forEach(item => {
        //console.log(`Product: ${item.product}, Gender: ${item.gender}, Genre: ${item.genre}, Age Group: ${item.agegroup}, Quantity: ${item.quantity}`);
    });**/
});