<template>

    <section class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Login</div>

                        <div class="card-body">
                            <form @submit="doLogin"
                                  method="POST">

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Email
                                        Address</label>

                                    <div class="col-md-6">
                                        <input v-model="email"
                                               id="email"
                                               class="form-control"
                                               :class="{ invalid: !errors.email.status }"
                                               name="email"
                                               type="email"
                                               required
                                               autocomplete="email"
                                               autofocus>

                                        <template v-if="errors.email.message">
                                            <strong class="invalid-feedback" role="alert">
                                                {{ errors.email.message }}
                                            </strong>
                                        </template>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                    <div class="col-md-6">
                                        <input v-model="password"
                                               id="password"
                                               type="password"
                                               class="form-control"
                                               name="password"
                                               required
                                               autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input v-model="remember"
                                                   class="form-check-input"
                                                   type="checkbox"
                                                   name="remember"
                                                   id="remember"
                                            />

                                            <label class="form-check-label" for="remember">
                                                Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>

                                        <router-link :to="{ name: 'password.request' }" class="btn btn-link">
                                            Forgot Your Password?
                                        </router-link>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
    import router from '../config/router';

    export default {
        name: 'Login',
        data() {
            return {
                email: '',
                password: '',
                remember: false,
                errors: {
                    email: {
                        status: true,
                        message: '',
                    }
                }
            };
        },
        methods: {
            doLogin(e) {
                e.preventDefault();

                axios.post('/api/login', {
                    email: this.email,
                    password: this.password
                }).then(res => {
                    this.$store.dispatch('login', {user: res.data.user, token: res.data.access_token}).then(() => {
                        this.$router.push({name: 'dashboard'});
                    });
                }).catch(err => {
                    console.error({err});
                });
            }
        }
    };
</script>
