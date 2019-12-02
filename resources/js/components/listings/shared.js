import axios from 'axios';
import slugify from 'slugify';
import {isTrue} from '../../helpers';

export const listing_mixin = {
    methods: {
        isSaved: (listing) => {
            const saved = listing.isSaved;
            return isTrue(saved);
        },
        view_listing: function (listing) {
            this.$router.push({
                name: 'listing.view',
                params: {
                    id: listing.id,
                    address: slugify(listing.address)
                }
            });
        },
        save_listing(listing) {
            if (!isTrue(listing.isSaved)) {
                axios.post(listing.saveUrl).then(() => {
                    listing.isSaved = true;
                    this.$store.dispatch('update_listing', listing);
                });
            }
        },
        remove_saved_listing(listing) {
            if (isTrue(listing.isSaved)) {
                axios.delete(listing.removeSavedUrl).then(() => {
                    listing.isSaved = false;
                    this.$store.dispatch('update_listing', listing);
                });
            }
        },
        highlight_on_map: (listing) => {
            let popup;

            for (let i in this.popups) {
                popup = this.popups[i];
                popup.popup.remove();

                if (popup.id === listing.id) {
                    popup.popup.addTo(this.map);
                    this.map.flyTo({
                        center: popup.popup._lngLat
                    });
                }
            }
        },
        // zoom_to_on_map: () => {
        //     let popup;
        //
        //     for (let i in this.popups) {
        //         if (this.popups.hasOwnProperty(i)) {
        //             popup = this.popups[i];
        //             popup.popup.remove();
        //
        //             if (popup.id === listing.id) {
        //                 popup.popup.addTo(this.map);
        //                 this.map.flyTo({
        //                     center: popup.popup._lngLat,
        //                     zoom: 18
        //                 });
        //             }
        //         }
        //     }
        // },
    }
};
