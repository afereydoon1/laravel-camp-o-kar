<template>
    <div class="col-12 my-3">
        <input type="file"
               @change="uploadProfile"
               :class="{ 'is-invalid' : $parent[form].errors.has(fieldName), classes }"
               v-bind="$attrs"
        >
        <img :src="profile_image" alt="" width="100">
        <has-error :form="$parent[form]" :field="fieldName"></has-error>
    </div>
</template>

<script>
    import { HasError } from 'vform';
    export default {
        inheritAttrs: false,
        name: "BasePhotoInput",

        components: {
            HasError
        },

        props: {
            value: {
                type: String,
                default: null
            },
            classes: {
                type: String,
                default: null
            },
            name: {
                type: String,
                required: true
            },
            field: {
                type: String,
                default: null
            },
            form: {
                type: String,
                default: 'form'
            }
        },

        computed: {
            profile_image() {
                let value = this.value;
                if (! value) value = '/profiles/avatar.png';
                return this.file ? this.file : value;
            },
            fieldName() {
                return this.field ? this.field : this.name;
            }
        },

        data() {
            return {
                file: null,
            }
        },

        methods: {
            uploadProfile(event) {
                let file = event.target.files[0];
                this.$emit('src', file.name);

                let reader = new FileReader();
                reader.onload = () => {
                    this.file = reader.result;

                    this.$emit('input', reader.result);
                };
                reader.readAsDataURL(file);
            }
        },
    }
</script>

<style scoped>

</style>
