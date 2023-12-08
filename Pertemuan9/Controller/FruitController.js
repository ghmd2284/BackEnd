import fruits from "../models/Fruits.js";

const index = () => {
    for (const fruit of fruits) {
        console.log(fruit)
    }
};

const store = dataArray => {
    fruits.push(dataArray);
    index();
};

const update = (position, dataArray) => {
    fruits[position] = dataArray;
    index();
};

const destroy = position => {
    fruits.splice(position, 1);
    index();
};

export {
    index,
    store,
    update,
    destroy
};


