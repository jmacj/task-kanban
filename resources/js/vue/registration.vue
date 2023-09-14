<template>
    <VApp>
        <VSheet class="pa-12" rounded>
            <VCard
                class="mx-auto px-6 py-8"
                max-width="400"
                title="User Registration"
            >
                <VCardTitle>
                    <VIcon icon="mdi-checkbox-marked-circle-auto-outline"/> User Registration
                </VCardTitle>
                <VDivider/>
                <VContainer>
                    <VTextField
                        v-model="name"
                        color="primary"
                        label="Full name"
                        variant="underlined"
                    />

                    <VTextField
                        v-model="email"
                        color="primary"
                        label="Email"
                        variant="underlined"
                    />

                    <VTextField
                        v-model="password"
                        color="primary"
                        label="Password"
                        placeholder="Enter your password"
                        variant="underlined"
                        type="password"
                    />
                </VContainer>

                <VDivider/>

                <VCardActions>
                    <VSpacer/>

                    <VBtn color="success" @click="register">
                        Complete Registration

                        <VIcon icon="mdi-chevron-right" end/>
                    </VBtn>
                </VCardActions>
            </VCard>
        </VSheet>
    </VApp>
</template>
<script>
import apiService from '../services/apiService';

export default {
    data: () => ({
        name: null,
        email: null,
        password: null,
    }),
    methods: {
        register() {
            apiService.register({
                name: this.name,
                email: this.email,
                password: this.password
            })
                .then(response => {
                    alert(response.data.message);
                    window.location.href = '/login';
                })
                .catch(error => {
                    alert(error.response.data.message);
                });
        }
    }
}
</script>
