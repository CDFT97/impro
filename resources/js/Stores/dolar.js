import axios from "axios";
import { defineStore } from "pinia";

export const useDolarStore = defineStore("dolar", {
    state: () => {
        return { 
            dolar_price: 0,
         };
    },
    getters: {},
    actions: {
        async getDolarPrice() {
            try {
                const { data } = await axios.get("/api/get-dolar-price");
                this.dolar_price = data.price;
            } catch (error) {
                console.error(error);
            }
        },
    },
});
