import { AjaxService } from "@/services/ajax.service";

export const AutocompleteService = {
    async fetch(ajaxUrl: string, query: string, action: string): Promise<Array<{ id: string; name: string }>> {
        const ajaxService = new AjaxService(ajaxUrl);
        const response = await ajaxService.post(action, { query });
        return response.data
    },
};
