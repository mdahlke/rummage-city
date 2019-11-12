import slugify from 'slugify';
import {is_true} from "../../helpers";

export const listing_mixin = {
    methods: {
        is_saved: (listing) => {
            const saved = listing.isSaved;
            return is_true(saved);
        },
        view_listing: function (listing) {
            this.$router.push({
                name: 'listing.view',
                params: {
                    id: listing.id,
                    address: slugify(listing.address)
                }
            })
        },
        save_listing(listing) {
            if (!is_true(listing.isSaved)) {
                axios.post(listing.saveUrl).then((e) => {
                    listing.isSaved = true;
                    this.$store.commit('update_listing', listing);
                });
            }
        },
        remove_saved_listing(listing) {
            if (is_true(listing.isSaved)) {
                axios.post(listing.removeSavedUrl).then((e) => {
                    listing.isSaved = false;
                    this.$store.commit('update_listing', listing);
                });
            }
        },
        highlight_on_map: (listing) => {
            let popup;

            for (let i in this.popups) {
                if (this.popups.hasOwnProperty(i)) {
                    popup = this.popups[i];
                    popup.popup.remove();

                    if (popup.id === listing.id) {
                        popup.popup.addTo(this.map);
                        this.map.flyTo({
                            center: popup.popup._lngLat
                        });
                    }
                }
            }
        },
        zoom_to_on_map: () => {
            let popup;

            for (let i in this.popups) {
                if (this.popups.hasOwnProperty(i)) {
                    popup = this.popups[i];
                    popup.popup.remove();

                    if (popup.id === listing.id) {
                        popup.popup.addTo(this.map);
                        this.map.flyTo({
                            center: popup.popup._lngLat,
                            zoom: 18
                        });
                    }
                }
            }
        },
    }
};
