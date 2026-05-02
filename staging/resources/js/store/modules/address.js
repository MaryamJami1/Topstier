import Mixin from './../../utils/mixin'
const loadState = () => ({
    addressesLoaded: false,
    addresses: [],
    guestUserAddress: JSON.parse(localStorage.getItem('guestUserAddress')) || [],
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        addressesLoaded(state){
            return state.addressesLoaded
        },
        getAddresses(state){
            return state.addresses
        },
        getDefaultShippingAddress(state){
            return state.addresses.find(address => address.default_shipping)
        },
        getDefaultBillingAddress(state){
            return state.addresses.find(address => address.default_billing)
        },
        getGuestUserAddress(state) {
            return state.guestUserAddress;
        },
    },
    mutations: {
        setAddresses(state, data){
            state.addressesLoaded = true;
            state.addresses = data;
        },
        addNewAddress(state, data){
            state.addresses.unshift(data)
        },
        addGuestUserAddress(state, data) {
            state.addressesLoaded = true;
            state.guestUserAddress.unshift(data);
            localStorage.setItem('guestUserAddress', JSON.stringify(state.guestUserAddress));
        },
        clearAddresses(state) {
            state.addresses = [];
        }
    },
    actions: {
        async fetchAddresses({commit,getters}){
            if(!getters.addressesLoaded){
                let res;
                const token = localStorage.getItem('shopAccessToken') || null;
                if(token){
                     res = await Mixin.methods.call_api("get", `user/addresses`);
                }else {
                     res = await Mixin.methods.call_api("get", `user/addresses?temp_user=${localStorage.getItem("shopTempUserId") || null}`);
                }

                if(res.data.success){
                    commit('setAddresses',res.data.data)
                }
            }
        },
        addAddress({commit},data){
            commit('addNewAddress',data)
        },
        
    }
}