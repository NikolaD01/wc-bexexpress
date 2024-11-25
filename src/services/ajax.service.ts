export class AjaxService {
    private ajaxUrl: string;

    constructor(ajaxUrl: string) {
        this.ajaxUrl = ajaxUrl;
    }

    public async post(endpoint: string, data: Record<string, any>): Promise<any> {
        try {
            const response = await fetch(this.ajaxUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    action: 'wc_bex_manager',
                    action_name: endpoint,
                    ...data,
                }).toString(),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return response.json();
        } catch (error) {
            console.error("AJAX Error:", error);
            throw error;
        }
    }
}
