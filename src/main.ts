import {CitySelector} from "@/classes/citySelector";
import {StreetSelector} from "@/classes/streetSelector";
import {Autocomplete} from "@/classes/autocomplete";

declare global {
    interface Window {
        admin_globals: {
            ajax_url: string;
            home_url: string;
        };

    }
}


const init = () => {
    setTimeout(function() {

        const autocomplete = document.getElementById('bex-autocomplete');

        if(autocomplete) {
            new Autocomplete('billing_state', window.admin_globals.ajax_url, 'get_municipalities')
            new Autocomplete('billing_city', window.admin_globals.ajax_url,'get_places')
            new Autocomplete('billing_address_1', window.admin_globals.ajax_url,'get_streets')


        } else {
            const stateContainer = document.getElementById("select2-billing_state-container");
            const cityInput = document.getElementById("billing_city");



            if (stateContainer && cityInput) {
                try {
                    new CitySelector(window.admin_globals.ajax_url, "select2-billing_state-container", "billing_city", "billing_address_1");
                    new StreetSelector(window.admin_globals.ajax_url, "billing_city", "billing_address_1");
                } catch (error) {
                    console.error("CitySelector Initialization Error:", error);
                }
            }
        }
    }, 1000);
};

document.addEventListener('DOMContentLoaded', init);

