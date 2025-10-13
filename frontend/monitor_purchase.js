document.addEventListener("DOMContentLoaded", async function () {
    while(true){
        /**let analysis = await eel.get_monitor_purchase_analysis()();
         * */
        initiate_monitor_purchase();
    }
});
async function initiate_monitor_purchase(){
    let analysis = await analyse_database();
    dashboard_display(analysis);
}
async function analyse_database(){
    let fetched_data = await fetch('../controller.php?act=get_monitor_purchase_analysis',
        {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }
    );
    return await fetched_data.json();
}
