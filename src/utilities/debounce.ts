export function debounce(callback: (...args: any[]) => void, delay: number): (...args: any[]) => void {
    let timeoutId: number | null = null;

    return (...args: any[]) => {
        if (timeoutId !== null) {
            clearTimeout(timeoutId);
        }

        timeoutId = window.setTimeout(() => {
            callback(...args);
            timeoutId = null;
        }, delay);
    };
}
