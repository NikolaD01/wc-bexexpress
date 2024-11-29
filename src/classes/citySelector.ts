import { AjaxService } from "@/services/ajax.service";
import { debounce } from "@/utilities/debounce";
export class CitySelector {
    private ajaxService: AjaxService;
    private readonly stateField: HTMLSelectElement;
    private cityField: HTMLSelectElement;
    private streetField: HTMLSelectElement;
    private readonly debouncedOnStateChange: (selectedText: string) => void;
    constructor(ajaxUrl: string, stateFieldId: string, cityFieldId: string, streetFieldId: string) {
        this.ajaxService = new AjaxService(ajaxUrl);

        const stateField = document.querySelector<HTMLSelectElement>(`#${stateFieldId}`);
        const cityField = document.querySelector<HTMLSelectElement>(`#${cityFieldId}`);
        const streetField : HTMLSelectElement = document.querySelector<HTMLSelectElement>(`#${streetFieldId}`);

        if (!stateField || !cityField) {
            throw new Error("State or city field not found in the DOM");
        }

        this.stateField = stateField;
        this.cityField = cityField;
        this.streetField = streetField;

        this.debouncedOnStateChange = debounce(
            this.onStateChangeFromTitleChange.bind(this),
            300
        );

        this.init();
    }

    private init(): void {
        if (this.stateField) {
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
    }

    // Handle state change caused by title change
    private async onStateChangeFromTitleChange(selectedText: string): Promise<void> {
        if (!selectedText) {
            this.clearCities();
            return;
        }
        this.clearStreets();

        try {
            const cities = await this.ajaxService.post("checkout_cities", {
                municipalities_name: selectedText,
            });

            this.populateCities(cities.data);
        } catch (error) {
            console.error("Failed to load cities:", error);
        }
    }



    private clearStreets() : void {
        this.streetField.innerHTML = "<option value=''>Izaberite ulicu</option>>";
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
