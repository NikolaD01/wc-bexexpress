import {AjaxService} from "@/services/ajax.service";

export class CreateOrder {
    private ajaxService: AjaxService;
    private button: HTMLElement;

    constructor(ajaxUrl: string, buttonId: string) {
        this.ajaxService = new AjaxService(ajaxUrl);

        const button: HTMLElement | null = document.getElementById(buttonId);
        if (!button) {
            console.error(`Button with ID ${buttonId} not found.`);
            return;
        }

        this.button = button;
        this.init();
    }

    private init(): void {
        this.button.addEventListener('click', (e) => {
            e.preventDefault();
            this.send();
        });
    }

    private async send(): Promise<void> {
        try {
            const data = this.getData();
            console.log(data)
            // Now send the FormData using AjaxService
            const response = await this.ajaxService.post("create_order", data);
            console.log("Order created successfully:", response);
        } catch (err) {
            console.error("Failed to create order:", err);
        }
    }


    private getData(): Record<string, any> {
        const hiddenInput = document.getElementById("post_ID") as HTMLInputElement;
        const postId = hiddenInput ? hiddenInput.value : null;

        const container = document.getElementById("bex_order_meta_box");
        const values: Record<string, string> = {};

        if (container) {
            const inputs = container.querySelectorAll<HTMLInputElement>('input[name^="bex"]');
            inputs.forEach((input) => {
                if (input.name) {
                    values[input.name] = input.value;
                }
            });
        } else {
            console.error("Container with ID 'bex_order_meta_box' not found.");
        }

        return {
            post_id: postId,
            meta_data: values,
        };
    }
}
