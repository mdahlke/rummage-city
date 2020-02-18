<template>
    <LayoutDashboard>
        <template v-slot:main>
            <h1>Profile Settings</h1>

            <form @submit.prevent
                  id="account-settings-form"
                  class="form"
                  method="POST"
            >

                <div class="form-group">
                    <label for="name">Name</label>
                    <input v-model="name"
                           id="name"
                           class="form-control"
                           name="name"
                           type="text"
                           required
                    />
                </div>

                <div class="form-group">
                    <label for="name">Email</label>
                    <input v-model="email"
                           id="email"
                           class="form-control"
                           name="email"
                           type="email"
                           required
                    />
                </div>

                <div class="form-group">
                    <label for="name">Address</label>
                    <p>You might be wondering why you can't enter your home address. We understand that it may make it
                        easier to add a listing by making you not enter your home address. However, this is information
                        that we feel we do not need to store since we make it pretty easy to find an address and select
                        it.</p>
                </div>

                <div class="form-group">
                    <label for="newPassword">Password</label>
                    <input v-model="newPassword"
                           id="newPassword"
                           class="form-control"
                           name="newPassword"
                           type="password"
                           required
                           autocomplete="none"
                    />
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input v-model="confirmPassword"
                           id="confirmPassword"
                           class="form-control"
                           name="confirmPassword"
                           type="password"
                           required
                           autocomplete="none"
                    />
                </div>

                <button @click="submitForm"
                        type="button"
                        class="btn btn-primary">
                    Update Information
                </button>

            </form>
        </template>
    </LayoutDashboard>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {ajaxForm} from '../form-helper';

    const LayoutDashboard = () => import('./layouts/Dashboard' /* webpackChunkName: 'js/chunks/layout-dashboard' */);

    export default {
        name: 'Settings',
        components: {
            LayoutDashboard
        },
        data() {
            return {
                name: '',
                email: '',
                newPassword: '',
                confirmPassword: '',
            };
        },
        created() {
            console.log(this.user);

            if (this.user.name) {
                this.name = this.user.name;
            }

            if (this.user.email) {
                this.email = this.user.email;
            }

        },
        computed: {
            ...mapGetters({
                user: 'getUser'
            }),
        },
        methods: {
            submitForm() {
                const form = document.getElementById('account-settings-form');

                ajaxForm(form, '/api/user/' + this.user.id + '/edit')
                    .then(res => {
                        if (res.data.status) {
                            this.$notification.success(res.data.message, {
                                message: 'Your listing information has been saved.',
                                timer: 5
                            });
                        }

                        this.$store.dispatch('getUser');
                    })
                    .catch(e => {
                        console.log('error updating user', {e});
                    });

            }
        }
    };
</script>

<style scoped>

</style>
