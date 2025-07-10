<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'title' => 'Understanding Oral Cancer',
                'body' => 'Oral cancer affects the tissues of the mouth and throat. Early detection significantly improves outcomes.',
                'image' => 'https://example.com/oral1.jpg',
            ],
            [
                'title' => 'Risk Factors for Oral Cancer',
                'body' => 'Tobacco and alcohol use are major risk factors for oral cancer. HPV infection is also a key contributor.',
                'image' => 'https://example.com/oral2.jpg',
            ],
            [
                'title' => 'HPV and Its Link to Oral Cancer',
                'body' => 'HPV type 16 has strong links to oral cancers, especially among younger populations who are non-smokers.',
                'image' => 'https://example.com/oral3.jpg',
            ],
            [
                'title' => 'How to Prevent Oral Cancer',
                'body' => 'Avoiding tobacco, limiting alcohol, and maintaining oral hygiene are key preventive measures.',
                'image' => 'https://example.com/oral4.jpg',
            ],
            [
                'title' => 'Oral Cancer Diagnosis',
                'body' => 'Diagnosis involves clinical examination, biopsy, and imaging techniques like MRI or CT scans.',
                'image' => 'https://example.com/oral5.jpg',
            ],
            [
                'title' => 'Treatment Options for Oral Cancer',
                'body' => 'Treatment depends on cancer stage and includes surgery, radiation, and chemotherapy.',
                'image' => 'https://example.com/oral6.jpg',
            ],
            [
                'title' => 'Signs and Symptoms of Oral Cancer',
                'body' => 'Symptoms may include lumps, white or red patches, mouth pain, or persistent sores.',
                'image' => 'https://example.com/oral7.jpg',
            ],
            [
                'title' => 'Oral Cancer in Young Adults',
                'body' => 'Recent data show oral cancer is rising in younger adults due to lifestyle and viral infections.',
                'image' => 'https://example.com/oral8.jpg',
            ],
            [
                'title' => 'Nutrition During Oral Cancer Treatment',
                'body' => 'Patients undergoing treatment need nutrient-rich, soft diets to maintain strength and recovery.',
                'image' => 'https://example.com/oral9.jpg',
            ],
            [
                'title' => 'Post-Treatment Care for Oral Cancer Patients',
                'body' => 'Post-treatment care includes follow-ups, speech therapy, and psychological support.',
                'image' => 'https://example.com/oral10.jpg',
            ],
            [
                'title' => 'Role of Dentists in Oral Cancer Screening',
                'body' => 'Dentists play a crucial role in early detection through routine check-ups.',
                'image' => 'https://example.com/oral11.jpg',
            ],
            [
                'title' => 'Impact of Smoking on Oral Cancer Risk',
                'body' => 'Smoking increases the risk of oral cancer dramatically, especially when combined with alcohol.',
                'image' => 'https://example.com/oral12.jpg',
            ],
            [
                'title' => 'Genetics and Oral Cancer',
                'body' => 'Certain genetic mutations are being studied for their role in oral cancer development.',
                'image' => 'https://example.com/oral13.jpg',
            ],
            [
                'title' => 'Innovative Therapies for Oral Cancer',
                'body' => 'Targeted therapy and immunotherapy are emerging as promising treatments for oral cancer.',
                'image' => 'https://example.com/oral14.jpg',
            ],
            [
                'title' => 'Rehabilitation After Oral Cancer Surgery',
                'body' => 'Surgical recovery may require physical therapy, prosthetics, and emotional care.',
                'image' => 'https://example.com/oral15.jpg',
            ],
            [
                'title' => 'Public Awareness Campaigns for Oral Cancer',
                'body' => 'Campaigns help raise awareness, leading to early diagnosis and better survival rates.',
                'image' => 'https://example.com/oral16.jpg',
            ],
            [
                'title' => 'Psychological Impact of Oral Cancer',
                'body' => 'The emotional toll of oral cancer includes anxiety, depression, and social withdrawal.',
                'image' => 'https://example.com/oral17.jpg',
            ],
            [
                'title' => 'Oral Cancer and Immune Response',
                'body' => 'Immune system response plays a significant role in cancer progression and treatment.',
                'image' => 'https://example.com/oral18.jpg',
            ],
            [
                'title' => 'Latest Research on Oral Cancer',
                'body' => 'Current research focuses on gene expression, biomarkers, and prevention methods.',
                'image' => 'https://example.com/oral19.jpg',
            ],
            [
                'title' => 'Global Statistics on Oral Cancer',
                'body' => 'Oral cancer rates vary globally, with high incidence in areas with tobacco chewing habits.',
                'image' => 'https://example.com/oral20.jpg',
            ],
        ];

        foreach ($articles as $article) {
            Article::create(array_merge($article, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}
