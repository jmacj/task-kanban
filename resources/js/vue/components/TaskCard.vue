<template>
    <VCard class="my-2" elevation="2" outlined>
        <VCardTitle>{{ item.title }}</VCardTitle>
        <VCardSubtitle>
            <VIcon>mdi-calendar</VIcon>
            {{ item.due_date }}
        </VCardSubtitle>
        <VDivider/>
        <VCardText> {{ item.description }}</VCardText>
        <VCardActions>
            <VDivider />
            <VBtn color="accent" elevation="2" icon @click="openDialog(index, itemIndex)">
                <VIcon>mdi-pencil</VIcon>
            </VBtn>
            <VBtn color="error" elevation="2" icon @click="openDeleteDialog(index, itemIndex)">
                <VIcon>mdi-trash-can</VIcon>
            </VBtn>
        </VCardActions>
    </VCard>
</template>

<script>
export default {
    props: {
        item: Object,
        index: Number,
        itemIndex: Number,
        openDialog: Function,
        openDeleteDialog: Function,
    },
};
</script>
