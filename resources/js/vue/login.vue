
<template>
    <VApp>
        <VSheet class="pa-12" rounded>
            <VCard class="mx-auto px-6 py-8" max-width="400">
                <VCardTitle>
                    <VIcon icon="mdi-checkbox-marked-circle-auto-outline"/> Task Management System
                </VCardTitle>
                <VDivider/>
                <VCardText>
                    <VForm
                        v-model="form"
                        @submit.prevent="onSubmit"
                    >
                        <VTextField
                            v-model="email"
                            :readonly="loading"
                            :rules="[required]"
                            prepend-inner-icon="mdi-email-outline"
                            class="mb-2"
                            label="Email"
                            clearable
                        />

                        <VTextField
                            v-model="password"
                            :readonly="loading"
                            :rules="[required]"
                            prepend-inner-icon="mdi-lock-outline"
                            type="password"
                            label="Password"
                            placeholder="Enter your password"
                            clearable
                        />

                        <br>

                        <VBtn
                            :disabled="!form"
                            :loading="loading"
                            color="success"
                            size="large"
                            type="submit"
                            variant="elevated"
                            block
                        >
                            Sign In
                        </VBtn>
                    </VForm>
                </VCardText>
                <VCardText class="text-center">
                    <a
                        class="text-blue text-decoration-none"
                        href="/register"
                    >
                        Sign up now <VIcon icon="mdi-chevron-right"/>
                    </a>
                </VCardText>
            </VCard>
        </VSheet>
    </VApp>
</template>
<script>

import apiService from '../services/apiService';
export default {
    data: () => ({
        form: false,
        email: null,
        password: null,
        loading: false,
    }),

    methods: {
        /**
         * submit the form
         */
        onSubmit() {
            if (!this.form) {
                return;
            }

            this.loading = true;

            setTimeout(() => (this.loading = false), 2000);
            apiService.login({
                email: this.email,
                password: this.password
            })
                .then(response => {
                    window.location.href = '/';
                })
                .catch(error => {
                    alert(error.response.data.message);
                });
        },

        /**
         * @param v
         * @returns {boolean|string}
         */
        required(v) {
            return !!v || 'Field is required';
        },
    },
}
</script>
