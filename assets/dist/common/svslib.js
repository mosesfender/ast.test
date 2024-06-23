"use strict";
if (!EventTarget.prototype.eventListener) {
    EventTarget.prototype['eventListener'] = function (atype, func, capture) {
        if (typeof arguments[0] === "object" && (!arguments[0].nodeType)) {
            return this.removeEventListener.apply(this, arguments[0]);
        }
        this.addEventListener(String(atype), func, capture);
        return arguments;
    };
}
if (!HTMLElement.prototype.closestType) {
    HTMLElement.prototype.closestType = function (type) {
        let node = this;
        if (node instanceof type) {
            return node;
        }
        else {
            try {
                node = node.parentElement;
                return node.closestType(type);
            }
            catch (err) {
                return null;
            }
        }
    };
}
function clone(obj) {
    let copy;
    if (null == obj || "object" != typeof obj)
        return obj;
    if (obj instanceof Date) {
        copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }
    if (obj instanceof Array) {
        copy = [];
        for (let i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }
    copy = {};
    for (let attr in obj) {
        if (obj.hasOwnProperty(attr)) {
            copy[attr] = clone(obj[attr]);
        }
    }
    return copy;
}
function extend(obj1, obj2) {
    if (obj2 instanceof Array) {
        if (!(obj1 instanceof Array)) {
            obj1 = [];
        }
        [].map.call(obj2, (val, idx, arr) => {
            if ('object' !== typeof val) {
                obj1.push(val);
            }
            else {
                extend(obj1[idx], val);
            }
        });
        return obj1;
    }
    for (let attr in obj2) {
        if ('object' !== typeof obj2[attr]) {
            obj1[attr] = obj2[attr];
        }
        else {
            if (!obj1[attr]) {
                obj1[attr] = {};
            }
            extend(obj1[attr], obj2[attr]);
        }
    }
    return obj1;
}
