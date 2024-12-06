import Fuse from 'fuse.js';
import { AutocompleteService } from "@/services/autocomplete.service";
import { debounce } from "@/utilities/debounce";

export class Autocomplete {
    private readonly containerId: string;
    private readonly ajaxUrl: string;
    private readonly action: string;

    private inputElement: HTMLInputElement | null = null;
    private hiddenInputElement: HTMLInputElement | null = null;
    private dropdown: HTMLUListElement | null = null;

    private fuse: Fuse<{ id: string; name: string }> | null = null;
    private readonly fetchDataDebounced: (...args: any[]) => void;
    private userSelected: boolean = false;

    constructor(containerId: string, ajaxUrl: string, action: string) {
        this.containerId = containerId;
        this.ajaxUrl = ajaxUrl;
        this.action = action;
        this.fetchDataDebounced = debounce(this.fetchData.bind(this), 300);
        this.initAutocomplete();
    }

    private initAutocomplete(): void {
        this.inputElement = document.querySelector(`#${this.containerId}_display`) as HTMLInputElement;
        this.hiddenInputElement = document.querySelector(`#${this.containerId}`) as HTMLInputElement;

        this.dropdown = document.createElement('ul');
        this.dropdown.classList.add('autocomplete-dropdown');
        this.inputElement?.parentNode?.appendChild(this.dropdown);

        this.inputElement?.addEventListener('input', (event) => {
            const query = (event.target as HTMLInputElement).value.trim();
            this.userSelected = false;
            if (query.length > 1) {
                this.fetchDataDebounced(query);
            } else {
                this.clearDropdown();
                if (this.hiddenInputElement) {
                    this.hiddenInputElement.value = query;
                }
            }
        });

        this.inputElement?.addEventListener('blur', () => {
            if (!this.userSelected && this.inputElement) {
                // If no option was selected, set hidden input to the typed value
                this.hiddenInputElement!.value = this.inputElement.value;
            }
        });
    }

    private async fetchData(query: string): Promise<void> {
        try {
            const results = await AutocompleteService.fetch(this.ajaxUrl, query, this.action);
            if (results) {
                this.setupFuse(results);
                this.updateDropdown(query);
            } else {
                this.clearDropdown();
            }
        } catch (error) {
            console.error('Fetch error:', error);
            this.clearDropdown();
        }
    }

    private setupFuse(data: Array<{ id: string; name: string }>): void {
        this.fuse = new Fuse(data, {
            keys: ['name'],
            threshold: 0.3,
        });
    }

    private updateDropdown(query: string): void {
        if (!this.dropdown || !this.fuse) return;

        this.clearDropdown();

        const results = this.fuse.search(query);

        results.forEach(({ item }) => {
            const li = document.createElement('li');
            li.textContent = item.name;
            li.dataset.id = item.id;

            li.addEventListener('click', () => {
                this.selectItem(item);
            });

            this.dropdown!.appendChild(li);
        });
    }

    private clearDropdown(): void {
        if (this.dropdown) this.dropdown.innerHTML = '';
    }

    private selectItem(item: { id: string; name: string }): void {
        if (this.inputElement && this.hiddenInputElement) {
            this.userSelected = true;
            this.inputElement.value = item.name;
            this.hiddenInputElement.value = item.id;
        }
        this.clearDropdown();
    }
}
