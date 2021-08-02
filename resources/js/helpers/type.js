const string = (value = '') => ({type: String, default: value});
const object = (value = {}) => ({type: Object, default: value});
const array = (value = []) => ({type: Array, default: value});
const number = (value = 0) => ({type: Number, default: value});
const boolean = (value = false) => ({type: Boolean, default: value});

export default {
    string,
    object,
    boolean,
    array,
    number,
}