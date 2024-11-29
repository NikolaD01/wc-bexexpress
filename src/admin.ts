import {CreateOrder} from "@/classes/createOrder";

declare global {
    interface Window {
        admin_globals: {
            ajax_url: string;
            home_url: string;
        };

    }
}


const init = () => {
    const ajax = window.admin_globals.ajax_url;
    try {
        new CreateOrder(ajax, 'bexCreateShipment');
    } catch (error) {
    }
};

document.addEventListener('DOMContentLoaded', init);

