<script
  setup
  lang="ts"
>
  import { ref } from 'vue';

  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head, Link } from '@inertiajs/vue3';
  import { usePage, useForm } from '@inertiajs/vue3';

  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import DangerButton from '@/Components/DangerButton.vue';

  import Modal from '@/Components/Modal.vue';


  const props = defineProps<{
    paginatedGroups: any
  }>();

  // console.log(props.paginatedGroups.data)
  const showCreateModal = ref(false);

  // const page = usePage()

  // console.log(page.props);

  const form = useForm({
    name: '',
  });

  const formDelete = useForm({});

  const createEconomicGroup = () => {
    form.post(route('economic.group.create'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
      },
      onError: () => {
        showCreateModal.value = false;
      }
    });
  };

  const deleteEconomicGroup = (groupId: number) => {
    formDelete.delete(route('economic.group.delete', groupId), {
      preserveScroll: true,
    });
  }
</script>

<template>

  <Head title="Grupos econômicos" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        Grupos Econômicos
      </h2>

      <PrimaryButton @click="() => { showCreateModal = true }">Criar</PrimaryButton>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <table class="w-full table-fixed border-collapse border border-gray-400">
          <thead class="text-gray-800 dark:text-gray-200">
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Criado em</th>
              <th scope="col">Editado em</th>
              <th scope="col">Opções</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(group, i) of paginatedGroups.data" :key="i" class="text-gray-800 dark:text-gray-200">
              <td class="border border-gray-400 p-3">{{ group.name }}</td>
              <td class="border border-gray-400 p-3">{{ new Date(group.created_at).toLocaleString('br-BR') }}</td>
              <td class="border border-gray-400 p-3">{{ new Date(group.updated_at).toLocaleString('br-BR') }}</td>
              <td class="border border-gray-400 p-3 flex justify-around">
                <Link :href="route('economic.group.edit', group.id)" :active="route().current('economic.group.list')"
                  class="text-base/10 align-baseline">
                Editar
                </Link>
                <form @submit.prevent="deleteEconomicGroup(group.id)">
                  <DangerButton type="submit" class="ml-2">Remover</DangerButton>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- <div v-for="(group, i) of paginatedGroups.data" :key="i" class="text-gray-800 dark:text-gray-200">
          {{ group }}
        </div> -->
      </div>
    </div>

    <Modal :show="showCreateModal" @close="() => { showCreateModal = false }">
      <section class="p-8">
        <header class="flex justify-between">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Criar Grupo Econômico
          </h2>

          <button type="button" @click="() => showCreateModal = false">
            <i class="pi pi-times"></i>
          </button>
          <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay
            secure.
          </p> -->
        </header>

        <form @submit.prevent="createEconomicGroup" class="mt-6 space-y-6">
          <div>
            <InputLabel for="name" value="Name" />

            <TextInput id="name" ref="name" v-model="form.name" type="text" class="mt-1 block w-full h-8 rounded-md"
              autocomplete="name" />

            <InputError :message="form.errors.name" class="mt-2" />
          </div>

          <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Cadastrar</PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
              <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">
                Saved.
              </p>
            </Transition>
          </div>
        </form>
      </section>
    </Modal>
  </AuthenticatedLayout>
</template>