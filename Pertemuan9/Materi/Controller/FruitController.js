// const fruits = require('../models/Fruits.js');
import fruits from "../models/Fruits.js";

function index() {
    console.log(fruits);
}

function store() {
    fruits.push("New Fruit");
}

// module.exports = {
//     index: index,
//     store: store,
// }


