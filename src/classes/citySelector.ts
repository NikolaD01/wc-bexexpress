import { SelectorService } from "@/services/selector.service";
import { debounce } from "@/utilities/debounce";

export class CitySelector {
    private readonly ajaxUrl: string;
    private readonly stateField: HTMLSelectElement;
    private cityField: HTMLSelectElement;
    private streetField: HTMLSelectElement;
    private readonly debouncedOnStateChange: (selectedText: string) => void;

    constructor(ajaxUrl: string, stateFieldId: string, cityFieldId: string, streetFieldId: string) {
        this.ajaxUrl = ajaxUrl;

        const stateField = document.querySelector<HTMLSelectElement>(`#${stateFieldId}`);
        const cityField = document.querySelector<HTMLSelectElement>(`#${cityFieldId}`);
        const streetField = document.querySelector<HTMLSelectElement>(`#${streetFieldId}`);

        if (!stateField || !cityField) {
            throw new Error("State or city field not found in the DOM");
        }

        this.stateField = stateField;
        this.cityField = cityField;
        this.streetField = streetField;

        this.debouncedOnStateChange = debounce(this.onStateChangeFromTitleChange.bind(this), 300);

        this.init();
    }

    private init(): void {
        const observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === "attributes" && mutation.attributeName === "title") {
                    const selectedText = this.stateField.title;
                    this.debouncedOnStateChange(selectedText);
                }
            }
        });

        observer.observe(this.stateField, { attributes: true });
    }

    private async onStateChangeFromTitleChange(selectedText: string): Promise<void> {
        if (!selectedText) {
            this.clearCities();
            return;
        }
        this.clearStreets();

        try {
            const cities = await SelectorService.fetchCities(this.ajaxUrl, selectedText);
            this.populateCities(cities.data);
        } catch (error) {
            console.error("Failed to load cities:", error);
        }
    }

    private clearStreets(): void {
        this.streetField.innerHTML = "<option value=''>Izaberite ulicu</option>";
        this.streetField.disabled = true;
    }

    private clearCities(): void {
        this.cityField.innerHTML = "<option value=''>Izaberite područje</option>";
        this.cityField.disabled = true;
    }

    private populateCities(cities: Array<{ id: string; name: string }>): void {
        this.cityField.innerHTML = "";

        cities.forEach((city) => {
            const option = document.createElement("option");
            option.value = city.id;
            option.textContent = city.name;

            this.cityField.appendChild(option);
        });

        this.cityField.disabled = false;
    }
}
