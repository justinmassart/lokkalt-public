<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $urls = [
            '0e1fb7f3-8660-411a-bae6-a0a5096e41f9.webp',
            '0fd6dfd0-93a5-4f4f-a69b-62793d1f5b85.webp',
            '12850351-36ef-483d-8353-5fe3f1cfe113.webp',
            '13397353-fc3e-4fee-ae9e-8945425fbb75.webp',
            '202c70ad-75bd-456e-ba1a-16168aeb14de.webp',
            '28625171-7b9b-4b11-b578-54764d4d44a4.webp',
            '28db4c8d-7888-45d6-a6e4-50821c86c11e.webp',
            '2c70643e-5374-47d5-bd1b-272a56fa046c.webp',
            '2ed075f1-d8c9-456f-985e-3de5243f0fa7.webp',
            '322a22cb-0746-4ec4-9af7-624398a74b0b.webp',
            '36301ca7-8c47-4e63-9eb6-834d87e26dbd.webp',
            '3eea8a07-f565-41dc-81b2-5527e8ff6b34.webp',
            '4c6e50a7-82f1-4688-8366-4f10d9665d4b.webp',
            '594aff73-03d3-4bc7-bd7c-27a9e463d720.webp',
            '61a1e76d-1397-4411-8a04-c06f1397f523.webp',
            '66db5690-1a69-4c8d-948e-34fa07dc6873.webp',
            '6b01485c-55c4-4b71-a97c-9ce46ac7e06d.webp',
            '6cc29ada-d4f8-4eb4-abc5-a2b245604c7c.webp',
            '74eb6ae9-ad69-4aa9-b208-5d6a06551cc5.webp',
            '76325499-a077-46f8-b7a8-41b804f3d41e.webp',
            '7b4974c5-d13b-4c8b-9f1b-f01dcddaa3f9.webp',
            '8100b068-3e64-4d74-a5cd-2eb08517a3b7.webp',
            '85af14e4-6faa-4b2c-a3f7-a991ace6b1d2.webp',
            '89ad09b6-03c1-4853-823c-a59495a25422.webp',
            '913372f7-3664-48c9-a85b-e29635bc8ebf.webp',
            '998c8588-9e90-440a-b2b4-80485a2bcaa6.webp',
            '9bdc3b77-ca96-455f-9c77-63d213e98b5b.webp',
            '9bdc3b79-f501-48cf-856f-4801bba82b68.webp',
            '9bdc3baa-1b7e-43fb-b299-8b24c8adc6bc.webp',
            '9bdc3bab-ed57-4252-be29-0ba029457176.webp',
            '9bdc3bad-73a5-47a2-82e5-df97668abb2c.webp',
            '9bdc3baf-7bd6-4fb5-a362-533f5a638113.webp',
            '9bdc3bdf-898a-46b7-921a-48f94c63df62.webp',
            '9bdc3be1-9ba9-41e5-92ec-1e09fa526ca6.webp',
            '9bdc3be3-5ed5-46bd-b9fb-cbc43126e57e.webp',
            '9bdc3be5-2ab4-4e8f-af93-9f609b068bcc.webp',
            '9bdc3c15-c235-422c-b199-9aed2640c3ea.webp',
            '9bdc3c18-6f4c-4eeb-bc45-778a79254d7e.webp',
            '9bdc3c1a-7d20-495a-b85a-4c847e27568c.webp',
            '9be05bb7-49e4-4b8f-90eb-28c84b0e1ed5.webp',
            '9be05bb9-5dbf-47dc-8943-059dd85a727a.webp',
            '9be06296-de12-491d-9653-c220a0632718.webp',
            '9be06298-b7f3-40f7-b27b-8b40920e79ff.webp',
            '9be0629a-49d0-4507-b177-e43701626666.webp',
            '9be06367-8f8e-4e46-8a57-9a7707eb78e4.webp',
            '9be065a4-09be-42b1-9eca-75bca6621a5f.webp',
            '9be065a5-f73b-45bd-b9e3-d8cd2f868112.webp',
            '9be07000-e702-42e5-9e2b-da3213e1e9b8.webp',
            '9be07002-adc7-4776-bbad-1fefb7414942.webp',
            '9be07004-26ff-4bcc-b6e1-27d2f4be5f74.webp',
            '9eca127d-00cd-4ba3-89b2-14f5ba8bdbfd.webp',
            'a0f227a0-4653-4849-a312-95dd7aa88973.webp',
            'a5823f9b-932e-49eb-b87e-83a86fe88450.webp',
            'ac532b79-087c-4e90-9151-f9a615003159.webp',
            'bf0d618d-54ab-4c0e-a6e7-8c0dcdff9927.webp',
            'da25733f-16fd-485b-b0c6-d833b42c4882.webp',
            'dea3aa59-15d6-4657-ac2c-52dfe7876f51.webp',
            'e78c0bbd-c3be-4169-a6ad-a71922eecc68.webp',
            'e8f4bf37-d1c3-42a1-a21f-fa9189df6c98.webp',
            'ef225a87-1c0a-49f9-a8fa-af0a2e49e3a5.webp',
            'efdbf231-8cef-4f44-96b1-4a615edda9fe.webp',
            '9c51aaf9-7f3c-456a-b801-0de71547a7f9.webp', '9c51aafb-f38d-4d2e-80d8-9f67796c84f5.webp', '9c51aafe-2001-487e-8db1-5a4c414dfe3a.webp', '9c51ab00-aa3a-4f73-959b-7f7e98a7260c.webp', '9c51ab2a-751d-4aa1-84d2-fba0dfcc0d50.webp', '9c51ab66-123a-437e-b0f6-02a9809e5329.webp', '9c51abad-8d55-4de1-a2bf-088747752acc.webp', '9c51abc6-f0ff-4284-b4c5-1d4590a0b2ea.webp',
            '9c51ac4d-7b84-472c-a522-75c8e644d654.webp', '9c51ac50-22a7-4116-9e6d-b1a1033af298.webp', '9c51ac51-d085-4e0f-a72c-fe456f1aeeea.webp', '9c51ac53-b734-4660-90f2-0ed82fcc5b0b.webp',
            '9c51aca4-ce9c-42be-823b-6480a72c23db.webp', '9c51aca7-788a-428c-b2fb-c1b6190e7197.webp', '9c51aca9-f677-48b2-9d08-d35aba9b24f0.webp', '9c51acac-e470-4a1a-bca4-d811ec552fca.webp',
            '9c51ace1-90f1-4a6f-aa32-62f5b95ae0f0.webp', '9c51ace3-b696-4475-9a7a-b3be8c5ce441.webp', '9c51ace6-2c1c-4e96-8caa-a8f103bbac39.webp', '9c51ace8-36b8-430f-a908-850c12a80e04.webp',
            '9c51ad0d-e1c4-4aff-9cab-cec43c47ebdd.webp', '9c51ad10-ee1b-457b-a56f-1c872d2ff6fa.webp'
        ];

        return [
            'url' => fake()->randomElement($urls),
        ];
    }
}
