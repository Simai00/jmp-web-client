<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <v-container>
        <v-layout mb-4>
            <v-text-field
                    :label="$t('search')"
                    append-icon="search"
                    hide-details
                    single-line
                    v-model="searchQuery"
            ></v-text-field>
        </v-layout>
        <v-data-table
                :headers="headers"
                :items="users"
                :search="searchQuery"
                v-if="users"
        >
            <template v-slot:items="props">
                <tr @click="$router.push(`/users/${props.item.id}`)" class="clickable">
                    <td>{{ props.item.username }}</td>
                    <td>{{ props.item.firstname }}</td>
                    <td>{{ props.item.lastname }}</td>
                </tr>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>

    export default {
        name: 'Overview',
        data: function () {
            return {
                searchQuery: '',
                headers: [
                    {
                        text: this.$t('user.username'),
                        align: 'left',
                        sortable: true,
                        value: 'username'
                    },
                    {
                        text: this.$t('user.firstName'),
                        align: 'left',
                        sortable: true,
                        value: 'firstname'
                    },
                    {
                        text: this.$t('user.lastName'),
                        align: 'left',
                        sortable: true,
                        value: 'lastname'
                    }
                ],
                isAdmin: false
            };
        },
        computed: {
            users() {
                return this.$store.state.users.all.items;
            }
        },
        mounted() {
            this.user = JSON.parse(window.localStorage.getItem('user'));
            this.isAdmin = this.user.isAdmin;
            this.$store.dispatch('users/getAll');
        }
    };
</script>

<style scoped>

</style>
