import {CitySelector} from "./classes/citySelector";
import {StreetSelector} from "./classes/streetSelector";

declare global {
    interface Window {
        admin_globals: {
            ajax_url: string;
            home_url: string;
        };

    }
}


const init = () => {
    try {
        setTimeout(function() {
            new CitySelector(window.admin_globals.ajax_url, "select2-billing_state-container", "billing_city", "billing_address_1");
            new StreetSelector(window.admin_globals.ajax_url, "billing_city", "billing_address_1");
        }, 1000);
    } catch (error) {
        console.error("CitySelector Initialization Error:", error);
    }
};

document.addEventListener('DOMContentLoaded', init);

