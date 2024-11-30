import { AjaxService } from "@/services/ajax.service";

export const BexService = {
    async createShipment(ajaxUrl: string, data: Record<string, any>): Promise<any> {
        const ajaxService = new AjaxService(ajaxUrl);
        return await ajaxService.post("create_order", data);
    },

    async getLabel(ajaxUrl: string, data: Record<string, any>): Promise<any> {
        const ajaxService = new AjaxService(ajaxUrl);
        return await ajaxService.post("get_label", data);
    }
};
