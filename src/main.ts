import oAuthRedirect from "./components/oAuthRedirect";
import oAuthListener from "./components/oAuthListener";
import pinterestPopulateLogSelect from "./components/populateLogSelect";

declare global {
    interface Window {
        admin_globals: {
            ajax_url: string;
            home_url: string;
            dashboard_url : string;
        };

    }
}


const init = () => {
    oAuthRedirect();
    oAuthListener();
    pinterestPopulateLogSelect();
};

document.addEventListener('DOMContentLoaded', init);

