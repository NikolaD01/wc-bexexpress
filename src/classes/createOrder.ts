import { BexService } from "@/services/bex.service";
import { GetLabel } from "@/classes/getLabel";

export class CreateOrder {
    private ajaxUrl: string;
    private button: HTMLElement;

    constructor(ajaxUrl: string, buttonId: string) {
        this.ajaxUrl = ajaxUrl;

        const button: HTMLElement | null = document.getElementById(buttonId);
        if (!button) {
            console.error(`Button with ID ${buttonId} not found.`);
            return;
        }

        this.button = button;
        this.init();
    }

    private init(): void {
        this.button.addEventListener("click", (e) => {
            e.preventDefault();
            this.send();
        });
    }

    private async send(): Promise<void> {
        try {
            const data = this.getData();
            const response = await BexService.createShipment(this.ajaxUrl, data);

            if (response.success) {
                this.replaceButton();
            }
            console.log("Order created successfully:", response);
        } catch (err) {
            console.error("Failed to create order:", err);
        }
    }

    private getData(): Record<string, any> {
        const hiddenInput = document.getElementById("post_ID") as HTMLInputElement;
        const postId = hiddenInput ? hiddenInput.value : null;

        const container = document.getElementById("bex_order_meta_box");
        const values: Record<string, string | boolean> = {};

        if (container) {
            const inputs = container.querySelectorAll<HTMLInputElement>('input[name^="bex"]');
            inputs.forEach((input) => {
                if (input.type === "checkbox") {
                    values[input.name] = input.checked;
                } else {
                    values[input.name] = input.value;
                }
            });
        } else {
            console.error("Container with ID 'bex_order_meta_box' not found.");
        }


        return {
            post_id: postId,
            meta_data: JSON.stringify(values),
        };
    }

    private replaceButton(): void {
        const newButton = document.createElement("button");
        newButton.className = "button";
        newButton.id = "bexCreateLabel";
        newButton.textContent = "Create Label";
        this.button.parentNode?.replaceChild(newButton, this.button);

        new GetLabel(this.ajaxUrl, "bexCreateLabel");
    }
}
