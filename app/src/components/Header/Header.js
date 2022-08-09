import { Nav, Navbar} from "react-bootstrap";
import React, {useState, useEffect} from "react";
import {motion} from "framer-motion";
import {UseJwt} from "../../shared/utils/JwtHelpers";

export const Header = () => {
    const scaleFactor = 3;
    const viewed = sessionStorage.getItem('viewed');
    const [logoAnimate, setLogoAnimate] = useState({});
    let jwtContent = UseJwt();
    const PageActive = useState({
        'Home': false,
        'Contact': false,
    });
    let logoIconRoll = {
        hidden: {
            rotate: !viewed ? 0 : -180
        },
        visible: {
            rotate: 0,
            transition: {
                duration: 2,
                ease: 'easeInOut'
            }
        }
    };
    let rectangles = (number) => {
        return {
            hidden: {
                opacity: 0
            },
            visible: {
                opacity: 1,
                transition: {
                    delay: number / 4,
                    duration: .5,
                    ease: 'easeInOut'
                }
            }
        };
    };

    let lines = (line, square) => {
        let number = 0;
        switch (line) {
            case 'positive':
                if (square % 2 !== 0) {
                    number = 2
                } else {
                    number = 6
                }
                break;
            case 'inner':
                if (square % 2 !== 0) {
                    number = 3
                } else {
                    number = 7
                }
                break;
            case 'negative':
                if (square % 2 !== 0) {
                    number = 4
                } else {
                    number = 8
                }
                break;
            case 'outer':
                if (square % 2 !== 0) {
                    number = 5
                } else {
                    number = 9
                }
                break;
        }
        return {
            hidden: {
                strokeDasharray: 1000,
                strokeDashoffset: 1000
            },
            visible: {
                strokeDashoffset: 0,
                transition: {
                    delay: number / 4,
                    duration: 2,
                    ease: 'easeInOut'
                }
            }
        };
    };
    switch ('/' + window.location.pathname.split('/')[1]) {
        case "/":
            PageActive['Home'] = true;
            break;
        case "/Contact":
            PageActive['Contact'] = true;
            break;
        default:
            break;
    }
    const animation = (location, line, square) => {
        let currentPage = window.location.pathname.split('/')[1]
        if (!viewed) {
            if (location === 'logo') {
                return logoIconRoll;
            } else {
                return {
                    hidden: {},
                    visible: {}
                };
            }
        } else {
            if (location === 'lines') {
                if (currentPage === 'Contact') {
                    return lines(line, square);
                } else
                    return {
                        hidden: {},
                        visible: {}
                    };
            } else if (location === 'rectangles') {
                if (currentPage === 'Contact') {

                    return {
                        hidden: {},
                        visible: {}
                    };
                } else {
                    return rectangles(line);
                }
            } else if (location === 'logo') {
                return {
                    hidden: {},
                    visible: {}
                };
            }
        }
    }
    const navigate = (destination) => {
        if(destination==='Home') {
            setLogoAnimate({
                x: 400, rotate: 720, transition: {
                    duration: .5,
                    ease: 'easeInOut'
                }
            });
        } else if(destination==='Contact'){
            setLogoAnimate({
                y: 100, transition: {
                    duration: .5,
                    ease: 'easeInOut'
                }
            })
        }
    }

    const scaleSvg = (number, row = 0) => {
        return (number + 10 * row / Math.sqrt(2)) * scaleFactor;
    }
    const logo = <motion.svg xmlns="http://www.w3.org/2000/svg" width={scaleSvg(100)} height={scaleSvg(30)}
                             initial={'hidden'} animate={'visible'}>
        <motion.g id="Logo_Image" transform="" variants={animation('logo')} animate={logoAnimate}>
            <motion.g id="Rectangle_1" variants={animation('rectangles', 1)}>{/*Middle Left*/}
                <motion.line variants={animation('lines', 'outer', 1)} x1={scaleSvg(5)} x2={scaleSvg(5)}
                             y1={scaleSvg(20.08)}
                             y2={scaleSvg(9.92)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 1)} x1={scaleSvg(5)} x2={scaleSvg(15)}
                             y1={scaleSvg(10)}
                             y2={scaleSvg(10)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 1)} x1={scaleSvg(15)} x2={scaleSvg(15)}
                             y1={scaleSvg(10)}
                             y2={scaleSvg(20)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 1)} x1={scaleSvg(15)} x2={scaleSvg(5)}
                             y1={scaleSvg(20)}
                             y2={scaleSvg(20)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
            <motion.g id="Rectangle_2" variants={animation('rectangles', 2)}>{/*Top Left*/}
                <motion.line variants={animation('lines', 'outer', 2)} x1={scaleSvg(4.92)} x2={scaleSvg(5.08, 1)}
                             y1={scaleSvg(10.08)} y2={scaleSvg(9.92, -1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 2)} x1={scaleSvg(5, 1)} x2={scaleSvg(5, 2)}
                             y1={scaleSvg(10, -1)} y2={scaleSvg(10)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'negative', 2)} x2={scaleSvg(5)} x1={scaleSvg(5, 1)}
                             y2={scaleSvg(10)}
                             y1={scaleSvg(10, 1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
                <motion.line variants={animation('lines', 'inner', 2)} x2={scaleSvg(5, 1)} x1={scaleSvg(5, 2)}
                             y2={scaleSvg(10, 1)}
                             y1={scaleSvg(10)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
            </motion.g>
            <motion.g id="Rectangle_3" variants={animation('rectangles', 3)}>{/*Top Middle*/}
                <motion.line variants={animation('lines', 'outer', 3)} x1={scaleSvg(4.92, 1)} x2={scaleSvg(15.08, 1)}
                             y1={scaleSvg(10, -1)} y2={scaleSvg(10, -1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 3)} x1={scaleSvg(15, 1)} x2={scaleSvg(15, 1)}
                             y1={scaleSvg(10, -1)} y2={scaleSvg(20, -1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 3)} x1={scaleSvg(15, 1)} x2={scaleSvg(5, 1)}
                             y1={scaleSvg(20, -1)}
                             y2={scaleSvg(20, -1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 3)} x1={scaleSvg(5, 1)} x2={scaleSvg(5, 1)}
                             y1={scaleSvg(20, -1)} y2={scaleSvg(10, -1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
            <motion.g id="Rectangle_4" variants={animation('rectangles', 4)}>{/*Top Right*/}
                <motion.line variants={animation('lines', 'outer', 4)} x1={scaleSvg(14.92, 1)} x2={scaleSvg(15.08, 2)}
                             y1={scaleSvg(9.92, -1)} y2={scaleSvg(10.08)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 4)} x1={scaleSvg(15, 2)} x2={scaleSvg(15, 1)}
                             y1={scaleSvg(10)}
                             y2={scaleSvg(10, 1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 4)} x1={scaleSvg(15, 1)} x2={scaleSvg(15)}
                             y1={scaleSvg(10, 1)}
                             y2={scaleSvg(10)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 4)} x1={scaleSvg(15)} x2={scaleSvg(15, 1)}
                             y1={scaleSvg(10)}
                             y2={scaleSvg(10, -1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
            <motion.g id="Rectangle_5" variants={animation('rectangles', 5)}>{/*Middle Right*/}
                <motion.line variants={animation('lines', 'outer', 5)} x1={scaleSvg(15, 2)} x2={scaleSvg(15, 2)}
                             y1={scaleSvg(20.08)}
                             y2={scaleSvg(9.92)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 5)} x1={scaleSvg(15, 2)} x2={scaleSvg(5, 2)}
                             y1={scaleSvg(20)}
                             y2={scaleSvg(20)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 5)} x1={scaleSvg(5, 2)} x2={scaleSvg(5, 2)}
                             y1={scaleSvg(20)}
                             y2={scaleSvg(10)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 5)} x1={scaleSvg(5, 2)} x2={scaleSvg(15, 2)}
                             y1={scaleSvg(10)}
                             y2={scaleSvg(10)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
            <motion.g id="Rectangle_6" variants={animation('rectangles', 6)}>{/*Bottom Right*/}
                <motion.line variants={animation('lines', 'outer', 6)} x2={scaleSvg(14.92, 1)} x1={scaleSvg(15.08, 2)}
                             y2={scaleSvg(20.08, 1)} y1={scaleSvg(19.92)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 6)} x1={scaleSvg(15, 1)} x2={scaleSvg(15)}
                             y1={scaleSvg(20, 1)}
                             y2={scaleSvg(20)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 6)} x1={scaleSvg(15)} x2={scaleSvg(15, 1)}
                             y1={scaleSvg(20)}
                             y2={scaleSvg(20, -1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 6)} x1={scaleSvg(15, 1)} x2={scaleSvg(15, 2)}
                             y1={scaleSvg(20, -1)} y2={scaleSvg(20)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
            <motion.g id="Rectangle_7" variants={animation('rectangles', 7)}>{/*Bottom Middle*/}
                <motion.line variants={animation('lines', 'outer', 7)} x1={scaleSvg(15.08, 1)} x2={scaleSvg(4.92, 1)}
                             y1={scaleSvg(20, 1)} y2={scaleSvg(20, 1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 7)} x1={scaleSvg(5, 1)} x2={scaleSvg(5, 1)}
                             y1={scaleSvg(20, 1)} y2={scaleSvg(10, 1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 7)} x1={scaleSvg(5, 1)} x2={scaleSvg(15, 1)}
                             y1={scaleSvg(10, 1)}
                             y2={scaleSvg(10, 1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 7)} x1={scaleSvg(15, 1)} x2={scaleSvg(15, 1)}
                             y1={scaleSvg(10, 1)} y2={scaleSvg(20, 1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
            <motion.g id="Rectangle_8" variants={animation('rectangles', 8)}>{/*Bottom Left*/}
                <motion.line variants={animation('lines', 'outer', 8)} x2={scaleSvg(4.92)} x1={scaleSvg(5.08, 1)}
                             y2={scaleSvg(19.92)} y1={scaleSvg(20.08, 1)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Outer*/}
                <motion.line variants={animation('lines', 'positive', 8)} x1={scaleSvg(5)} x2={scaleSvg(5, 1)}
                             y1={scaleSvg(20)}
                             y2={scaleSvg(20, -1)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Positive*/}
                <motion.line variants={animation('lines', 'inner', 8)} x1={scaleSvg(5, 1)} x2={scaleSvg(5, 2)}
                             y1={scaleSvg(20, -1)}
                             y2={scaleSvg(20)} stroke="#252422" strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Inner*/}
                <motion.line variants={animation('lines', 'negative', 8)} x2={scaleSvg(5, 1)} x1={scaleSvg(5, 2)}
                             y2={scaleSvg(20, 1)} y1={scaleSvg(20)} stroke="#252422"
                             strokeWidth={scaleSvg(.8) + 'px'}/>
                {/*Negative*/}
            </motion.g>
        </motion.g>
        <g id="Logo_Words" transform={"translate(" + scaleSvg(30) + ")"}>
            <text id="WILTY" className="text" transform={"translate(" + scaleSvg(15.6) + " " + scaleSvg(11) + ")"}
                  fill="#252422">
                <tspan x="0" y="19.0">Would I Lie To You</tspan>
            </text>
            <text id="Online" className="text"
                  transform={"translate(" + scaleSvg(21.6) + " " + scaleSvg(27.072) + ")"}
                  fill="#252422">
                <tspan x="0" y="-.5">Online</tspan>
            </text>
        </g>
    </motion.svg>

    !viewed && sessionStorage.setItem('viewed', 'true');

    return (
        <Navbar bg="light" expand="lg" className={'headerBorder'}>
            <Navbar.Brand href="#home">{logo}</Navbar.Brand>
            <Navbar.Toggle aria-controls="basic-navbar-nav"/>
            <Navbar.Collapse id="basic-navbar-nav">
                <Nav className="mr-auto">
                    <Nav.Link disabled={PageActive['Home']} active={PageActive['Home']} onClick={() => {
                        navigate('Home');
                        setTimeout(() => {
                            window.location = "/"
                        }, 500)
                    }}><p>Home</p>
                    </Nav.Link>
                    <Nav.Link disabled={PageActive['Contact']} active={PageActive['Contact']} onClick={() => {
                        navigate("Contact");
                        setTimeout(() => {
                            window.location = "/Contact"
                        }, 500)}}>
                        <p>Contact Me</p></Nav.Link>
                </Nav>
            </Navbar.Collapse>
        </Navbar>
    )
}