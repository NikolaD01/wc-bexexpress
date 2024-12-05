import { AjaxService } from "@/services/ajax.service";

export const AutocompleteService = {
    async fetchMunicipalities(ajaxUrl: string, query: string): Promise<Array<{ id: string; name: string }>> {
        const ajaxService = new AjaxService(ajaxUrl);
        const response = await ajaxService.post("get_municipalities", { query });
        return response.data
    },
};
