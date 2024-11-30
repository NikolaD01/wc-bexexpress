import { BexService } from "@/services/bex.service";

export class GetLabel {
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
            this.get();
        });
    }

    private async get(): Promise<void> {
        try {
            const data = this.getData();
            const response = await BexService.getLabel(this.ajaxUrl, data);

            if (response.success) {
                const fileUrl = response.data.fileUrl;
                if (fileUrl) {
                    const a = document.createElement("a");
                    a.href = fileUrl;
                    a.download = "label.pdf";
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            } else {
                console.error("Failed to create order:", response.data);
            }
        } catch (err) {
            console.error(err);
        }
    }

    private getData(): Record<string, any> {
        const hiddenInput = document.getElementById("post_ID") as HTMLInputElement;
        const postId = hiddenInput ? hiddenInput.value : null;

        return {
            post_id: postId,
        };
    }
}
