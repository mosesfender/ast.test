/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

interface EventTarget {
    eventListener(atype: string | object, func?: EventListenerOrEventListenerObject, capture?: any): any;
}

if (!EventTarget.prototype.eventListener) {
    EventTarget.prototype['eventListener'] = function (
        atype: string | object, func?: EventListenerOrEventListenerObject, capture?: any): any {
        if (typeof arguments[0] === "object" && (!arguments[0].nodeType)) {
            return (this as HTMLElement).removeEventListener.apply(this, arguments[0]);
        }
        // @ts-ignore
        (this as HTMLElement).addEventListener(String(atype), func, capture);
        return arguments;
    };
}

interface HTMLElement {
    /**
     * Находит элемент с указанным типом в иерархии объектов вверх от текщего элемента.
     * @param type Искомый тип объекта (имя конструктора)
     */
    closestType(type: any): HTMLElement | null;
}

if (!HTMLElement.prototype.closestType) {
    HTMLElement.prototype.closestType = function (type: any) {
        let node: HTMLElement | null = this as HTMLElement;
        if (node instanceof type) {
            return node;
        } else {
            try {
                node = node.parentElement;
                // @ts-ignore
                return node.closestType(type);
            } catch (err) {
                return null;
            }
        }
    }
}

function clone(obj: any): any {
    let copy;

    if (null == obj || "object" != typeof obj) return obj;

    // Handle Date
    if (obj instanceof Date) {
        copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        copy = [];
        for (let i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    copy = {};
    for (let attr in obj) {
        if (obj.hasOwnProperty(attr)) { // @ts-ignore
            copy[attr] = clone(obj[attr]);
        }
    }
    return copy;
}

function extend(obj1: any, obj2: any): any {
    if (obj2 instanceof Array) {
        if (!(obj1 instanceof Array)) {
            obj1 = [];
        }
        [].map.call(obj2, (val: any, idx: number, arr: any[])=>{
            if ('object' !== typeof val) {
                // @ts-ignore
                obj1.push(val);
            } else {
                // @ts-ignore
                extend(obj1[idx], val);
            }
        });
        return obj1;
    }
    for (let attr in obj2) {
        // @ts-ignore
        if ('object' !== typeof obj2[attr]) {
            // @ts-ignore
            obj1[attr] = obj2[attr];
        } else {
            if (!obj1[attr]) {
                obj1[attr] = {};
            }
            // @ts-ignore
            extend(obj1[attr], obj2[attr]);
        }
    }
    return obj1;
}
