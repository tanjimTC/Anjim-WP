import Table from './Components/Table';
import Graph from './Components/Graph';
import Settings from './Components/Settings';

export const routes = [
    {
        path: '/',
        name: 'table',
        component: Table
    },
    {
        path: '/graph',
        name: 'graph',
        component: Graph
    },
    {
        path: '/settings',
        name: 'settings',
        component: Settings
    }
];
