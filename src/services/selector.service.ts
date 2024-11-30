import { AjaxService } from "@/services/ajax.service";

export const SelectorService = {
    async fetchCities(ajaxUrl: string, municipalitiesName: string): Promise<any> {
        const ajaxService = new AjaxService(ajaxUrl);
        return await ajaxService.post("checkout_cities", { municipalities_name: municipalitiesName });
    },

    async fetchStreets(ajaxUrl: string, placeId: string): Promise<any> {
        const ajaxService = new AjaxService(ajaxUrl);
        return await ajaxService.post("checkout_streets", { place_id: placeId });
    },
};
