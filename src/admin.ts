import {CreateOrder} from "@/classes/createOrder";
import {GetLabel} from "@/classes/getLabel";

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
        new GetLabel(ajax, 'bexCreateLabel');
    } catch (error) {
    }
};

document.addEventListener('DOMContentLoaded', init);

