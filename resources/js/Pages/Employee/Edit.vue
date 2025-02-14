<script
  setup
  lang="ts"
>
  import InputLabel from '@/Components/InputLabel.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import { Head, useForm } from '@inertiajs/vue3';

  const props = defineProps<{
    employee: any
  }>();

  const form = useForm({
    name: props.employee.name,
    email: props.employee.email,
    cpf: props.employee.cpf,
  });

  const edit = () => {
    form.patch(route('employees.update', props.employee.id), {
      preserveScroll: true,
    });
  }
</script>

<template>

  <Head title="Grupos econômicos" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        Editar Colaborador
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <form @submit.prevent="edit">
          <div>
            <InputLabel for="name" value="Nome" />

            <TextInput id="name" ref="name" v-model="form.name" type="text" class="mt-1 block w-full h-8 rounded-md"
              autocomplete="name" />

            <InputError :message="form.errors.name" class="mt-2" />
          </div>
          
          <div>
            <InputLabel for="email" value="Email" />

            <TextInput id="email" ref="email" v-model="form.email" type="text" class="mt-1 block w-full h-8 rounded-md"
              autocomplete="email" />

            <InputError :message="form.errors.email" class="mt-2" />
          </div>
          
          <div>
            <InputLabel for="cpf" value="CPF" />

            <TextInput id="cpf" ref="cpf" v-model="form.cpf" type="text" class="mt-1 block w-full h-8 rounded-md"
              autocomplete="cpf" />

            <InputError :message="form.errors.cpf" class="mt-2" />
          </div>

          <div class="flex flex-row-reverse items-center gap-4 mt-5">
            <PrimaryButton :disabled="form.processing">Editar</PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
              <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">
                Saved.
              </p>
            </Transition>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>