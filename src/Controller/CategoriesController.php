<?php
namespace App\Controller;

use App\Model\Entity\Category;
use App\Model\Table\CategoriesTable;
use Cake\Datasource\ResultSetInterface;

/**
 * Categories Controller
 *
 * @property CategoriesTable $Categories
 * @method Category[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    /**
     * View method
     *
     * @param string $slug Category slug
     * @return void
     */
    public function view($slug)
    {
        /** @var Category $category */
        $category = $this->Categories->find()->where(['slug' => $slug])->firstOrFail();

        $this->set([
            'category' => $category,
            'titleForLayout' => $category->name
        ]);
    }
}
