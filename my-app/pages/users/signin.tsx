import React from 'react'
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as Yup from "yup";
import router, { useRouter } from 'next/router';
import { useEffect } from 'react';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Link from 'next/link';
import Cookies from 'js-cookie';


const validationSchema = Yup.object().shape({
    email: Yup.string().email("Invalid email address").required("Email is required").matches(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/),
    password: Yup.string().min(8, "Password must be at least 8 characters").required("Password is required"),
    });
// const router = useRouter() 
    

const SignIn:React.FC = () => {
    return (
        <>
            <section className='w-100 d-flex align-items-center justify-content-center flex-column py-5' style={{height:"fit-content"}}>
                <ToastContainer />
            <h3 className='text-center'><b>Welcome to <span style={{color:"#0077B5"}}>THE APP</span>. <br />Sign In to continue </b></h3>
                <Formik 
                    initialValues={{email: "", password: ""}}
                    validationSchema={validationSchema}
                    onSubmit={(values) => {
                        console.log(values);
                            axios.post('http://localhost/socialMedia/socialMediaApp/signin.php', values, {
                                headers: {
                                    'Access-Control-Allow-Origin': '*',
                                    'Content-Type': 'application/json'
                                }
                            }).then((res) => {
                                console.log(res);
                                if (res.data.status === true) {
                                    console.log(res.data)
                                    toast(res.data.message)
                                    console.log(res.data.token)
                                    Cookies.set("token", res.data.token)
                                    Cookies.set("user_id", JSON.stringify(res.data.user_id))
                                    // localStorage.setItem("token", res.data.token)
                                    router.push('/users/dashboard')
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
                                <label style={{color:"#0077B5"}}>Email</label>
                                <Field className="form-control w-100 my-2" name="email" type="email" />
                                {errors.email && touched.email ? <div style={{color:"red"}}>{errors.email}</div> : null}

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

export default SignIn