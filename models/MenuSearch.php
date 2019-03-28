<?php

namespace x51\yii2\modules\menu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use x51\yii2\modules\menu\models\Menu;

/**
 * MenuSearch represents the model behind the search form of `x51\yii2\modules\menu\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sort', 'active', 'user_id', 'parent_id'], 'integer'],
            [['menu', 'name', 'url_path', 'url_params', 'permission', 'parent_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Menu::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sort' => $this->sort,
            'active' => $this->active,
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
        ]);

        $query->andFilterWhere(['like', 'menu', $this->menu])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url_path', $this->url_path])
            ->andFilterWhere(['like', 'url_params', $this->url_params])
            ->andFilterWhere(['like', 'permission', $this->permission]);

        return $dataProvider;
    }
}
