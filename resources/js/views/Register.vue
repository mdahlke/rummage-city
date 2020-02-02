<template>

    <section class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>

                        <div class="card-body">
                            <form @prevent.submit
                                  method="POST">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                    <div class="col-md-6">
                                        <input v-model="name"
                                               id="name"
                                               type="text"
                                               class="form-control"
                                               name="name"
                                               required
                                               autocomplete="name"
                                               autofocus>

                                        <template v-if="errors.name.message">
                                            <strong class="invalid-feedback" role="alert">
                                                {{ errors.name.message }}
                                            </strong>
                                        </template>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Email
                                        Address</label>

                                    <div class="col-md-6">
                                        <input id="email"
                                               class="form-control"
                                               :class="{ invalid: !errors.email.status }"
                                               name="email"
                                               type="email"
                                               v-model="email"
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
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm
                                        Password</label>

                                    <div class="col-md-6">
                                        <input v-model="passwordConfirm"
                                               id="password-confirm"
                                               type="password"
                                               class="form-control"
                                               name="password_confirmation"
                                               required
                                               autocomplete="new-password">
                                    </div>
                                </div>


                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>

                                        <router-link :to="{ name: 'login' }" class="btn btn-link">
                                            Already have an account?
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
    export default {
        name: 'Login',
        data() {
            return {
                name: '',
                email: '',
                password: '',
                passwordConfirm: '',
                errors: {
                    email: {
                        status: true,
                        message: '',
                    },
                    name: {
                        status: true,
                        message: '',
                    },
                    password: {
                        status: true,
                        message: '',
                    },
                }
            };
        }
    };
</script>

<style scoped>

</style>
