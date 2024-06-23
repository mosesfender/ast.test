interface EventTarget {
    eventListener(atype: string | object, func?: EventListenerOrEventListenerObject, capture?: any): any;
}
interface HTMLElement {
    closestType(type: any): HTMLElement | null;
}
declare function clone(obj: any): any;
declare function extend(obj1: any, obj2: any): any;
