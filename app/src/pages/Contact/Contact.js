import React from "react";
import {Container, Col, Row} from "react-bootstrap";

export const Contact = () => {
    return (
        <Container>
            <Row className={"mt-4 justify-content-around"}>
                <h2>Wanna get in touch?</h2>
            </Row>
            <Row className={"mr-0 d-flex align-items-center justify-content-around pb-5"}>
                <Col className={'col-12 text-left pt-3'}>
                    <p>
                        <a href = "mailto: wilty@johnthe.dev">Send me an email!</a> I look forward to hearing from you!
                    </p>

                </Col>
            </Row>
        </Container>
    )
};