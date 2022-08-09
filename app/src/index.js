import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter} from "react-router-dom";
import {Route, Switch} from "react-router";
import {applyMiddleware, createStore} from "redux";
import thunk from "redux-thunk";
import {Provider} from 'react-redux';
import './css/main.css';
import './css/WILTY.scss';

import {Container} from "react-bootstrap";
import {Row} from "react-bootstrap";
import {Col} from "react-bootstrap";
import {Home} from "./pages/Home/Home";
import {Contact} from "./pages/Contact/Contact";
import {Header} from "./components/Header/Header";

const store = createStore(combinedReducers, applyMiddleware(thunk));
const Routing = (store) => (
    <>
        <Provider store={store}>
            <BrowserRouter>
                <Container fluid className={"bg-light"}>
                    <Row className={'sticky-top d-flex align-items-center'}>
                        <Col>
                            <Header/>
                        </Col>
                    </Row>
                    <Row>
                        <Col className={"bg-light"}>
                            <Switch>
                                <Route exact path="/" component={Home}/>
                                <Route exact path='/Contact' component={Contact}/>
                            </Switch>
                        </Col>
                    </Row>
                </Container>
            </BrowserRouter>
        </Provider>
    </>
);
ReactDOM.render(Routing(store), document.querySelector('#root'));