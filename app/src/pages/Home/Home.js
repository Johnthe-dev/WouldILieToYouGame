import React from "react";
import {Container, Col, Row} from "react-bootstrap";

export const Home = () => {
    return (
        <Container>
            <Row className={"mt-4 justify-content-around"}>
                <h2>Would I Lie To You?</h2>
            </Row>
            <Row className={"mr-0 d-flex align-items-center justify-content-around pb-5"}>
                <Col className={'col-12 text-left pt-3'}>
                    <p>
                        This web app was built out of a love of Would I Lie To You, a british panel show starring David Mitchell,
                        Lee Mack, and Rob Brydon. I hope you enjoy!
                    </p>
                    <p>What you will need to play:</p>
                    <ul>
                        <li>
                            An audio or video conferencing app
                        </li>
                        <li>
                            At least four people
                        </li>
                        <li>
                            A willingness to participate in a bit of make believe for fun.
                        </li>
                    </ul>
                </Col>
            </Row>
        </Container>
    )
};