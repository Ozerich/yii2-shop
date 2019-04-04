import React from 'react';
import ReactDOM from 'react-dom';

import App from './js/App';
import './scss/index.scss';

const nodeElement = document.getElementById('react-app-category-conditional-settings');

const categoryId = nodeElement.dataset['categoryId'];

ReactDOM.render(<App categoryId={categoryId} />, nodeElement);
