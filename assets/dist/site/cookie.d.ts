declare module cookie {
    function read(name: string): string;
    function write(name: string, value: string, days?: number): void;
    function remove(name: string): void;
}
