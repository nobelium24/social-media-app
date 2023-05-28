import React from 'react'
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as Yup from "yup";
import router, { useRouter } from 'next/router';
import { useEffect } from 'react';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Link from 'next/link';


const validationSchema = Yup.object().shape({
    firstName: Yup.string().min(2, 'Too Short!').max(50, 'Too Long!').required('Required').matches(/^[a-zA-Z]+$/),
    lastName: Yup.string().min(2, 'Too Short!').max(50, 'Too Long!').required('Required').matches(/^[a-zA-Z]+$/),
    email: Yup.string().email("Invalid email address").required("Email is required").matches(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/),
    password: Yup.string().min(8, "Password must be at least 8 characters").required("Password is required"),
    userName: Yup.string().min(2, 'Too Short!').max(50, 'Too Long!').required('Required').matches(/^(?=.*[^\s]).{8,}$/),

    });
// const router = useRouter() 
    

const Signup:React.FC = () => {
    return (
        <>
            <section className='w-100 d-flex align-items-center justify-content-center flex-column py-5' style={{height:"fit-content"}}>
                <ToastContainer />
            <h3 className='text-center'><b>Welcome to <span style={{color:"#0077B5"}}>THE APP</span>. <br />Signup to continue </b></h3>
                <Formik 
                    initialValues={{ firstName: "", lastName: "", email: "", password: "", userName: "" }}
                    validationSchema={validationSchema}
                    onSubmit={(values) => {
                        console.log(values);
                            axios.post('http://localhost/socialMedia/socialMediaApp/register.php', values, {
                                headers: {
                                    'Access-Control-Allow-Origin': '*',
                                    'Content-Type': 'application/json'
                                }
                            }).then((res) => {
                                // console.log(res);
                                if (res.data.status === true) {
                                    toast(res.data.message)
                                    router.push('/users/signin')
                                }else{
                                    toast(res.data.message)
                                }
                            }).catch((err)=>{
                                // console.log(err);
                                toast(err.response.data.message)
                            })
                        
                    }}>
                        {({ errors, touched }) => (
                            <Form className='w-75 px-4 py-5 d-flex flex-column '>
                                <label style={{color:"#0077B5"}}>First name</label>
                                <Field className="form-control w-100 my-2" name="firstName" />
                                {errors.firstName && touched.firstName ? (<div style={{color:"red"}}>{errors.firstName}</div>) : null}

                                <label style={{color:"#0077B5"}}>Last name</label>
                                <Field className="form-control w-100 my-2" name="lastName" />
                                {errors.lastName && touched.lastName ? (<div style={{color:"red"}}>{errors.lastName}</div>) : null}

                                <label style={{color:"#0077B5"}}>Email</label>
                                <Field className="form-control w-100 my-2" name="email" type="email" />
                                {errors.email && touched.email ? <div style={{color:"red"}}>{errors.email}</div> : null}

                                <label style={{color:"#0077B5"}}>User name</label>
                                <Field className="form-control w-100 my-2" name="userName" />
                                {errors.userName && touched.userName ? <div style={{color:"red"}}>{errors.userName}</div> : null}

                                <label style={{color:"#0077B5"}}>Password</label>
                                <Field className="form-control w-100 my-2" name="password" type="password" />
                                {errors.password && touched.password ? <div style={{color:"red"}}>{errors.password}</div> : null}

                                <button type="submit" className='btn btn-info my-2' 
                                style={{backgroundColor:"#0077B5", color:"white", border:"none"}}>Submit</button>

                                <p>Have an account? <Link href="/user/signin" style={{color:"#0077B5"}}>Sign in</Link></p>
                                <p className='text-center'><Link href='/' style={{color:"#0077B5"}}>Click here</Link> to go to landing page</p>


                            </Form>
                        )}   
                </Formik>        
            </section>
        </>
    )
}

export default Signup