import { SelectorService } from "@/services/selector.service";
import { debounce } from "@/utilities/debounce";

export class StreetSelector {
    private readonly ajaxUrl: string;
    private readonly cityField: HTMLSelectElement;
    private streetField: HTMLSelectElement;
    private readonly debouncedOnCityChange: (id: string) => void;

    constructor(ajaxUrl: string, cityFieldId: string, streetFieldId: string) {
        this.ajaxUrl = ajaxUrl;

        const cityField = document.querySelector<HTMLSelectElement>(`#${cityFieldId}`);
        const streetField = document.querySelector<HTMLSelectElement>(`#${streetFieldId}`);

        if (!streetField || !cityField) {
            throw new Error("Street or city field not found in the DOM");
        }

        this.cityField = cityField;
        this.streetField = streetField;

        this.debouncedOnCityChange = debounce(this.onCityChange.bind(this), 300);

        this.init();
    }

    private init(): void {
        this.cityField.addEventListener("change", (event) => {
            const selectedId = (event.target as HTMLSelectElement).value;
            this.debouncedOnCityChange(selectedId);
        });
    }

    private async onCityChange(id: string): Promise<void> {
        if (!id) {
            this.clearStreets();
            return;
        }

        try {
            const response = await SelectorService.fetchStreets(this.ajaxUrl, id);
            this.populateStreets(response.data);
        } catch (error) {
            console.error("Failed to load streets:", error);
        }
    }

    private clearStreets(): void {
        this.streetField.innerHTML = "<option value=''>Select a city first</option>";
        this.streetField.disabled = true;
    }

    private populateStreets(streets: Array<{ id: string; name: string }>): void {
        this.streetField.innerHTML = "";

        streets.forEach((street) => {
            const option = document.createElement("option");
            option.value = street.id;
            option.textContent = street.name;

            this.streetField.appendChild(option);
        });

        this.streetField.disabled = false;
    }
}
